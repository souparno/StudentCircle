var base = {"address": "", "landlord": ""};
var submenu_defaults = ["Floor", "Window", "Door", "Lights", "Curtains"];
var data = {};
var parent_section={};
var sections = {};
var dataset = {};
var selected_section = null;
var selected_sub = null;
var tagprefix = "legacy-";
var tagtitle = "";

function isNumber(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

$(document).ready(function() {

    //getusermedia api cross-browser support
    navigator.getUserMedia = (navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia);

    if (navigator.getUserMedia === null) {
        tagprefix = "legacy-";
    }
    else {
        navigator.getUserMedia({video: true, audio: false}, function() {
            tagprefix = "";
        }, function() {
            tagprefix = "legacy-";
        });
    }
    // end of all the api 

    //Checking if the user is logging in as admin
    var path_var = PATH.split("/");
    var is_admin = 0;
    for (i = path_var.length; i >= 0; i--) {
        if (path_var[i] === "admin") {
            is_admin = 1;
            break;
        }
    }

    //Loading the respective view for the user
    if (is_admin)
        loadContent('admin.html', adminPage);
        //loadContent('sections.html', sectionsPage);
    else
        loadContent('address.html', addressPage);


    //Ajax function to get the json from the menus.txt page
    (function() {
        $.get('core.php?a=fa', function(data) {
            dataset = $.parseJSON(data);
        });
    }());


});


function loadContent(page, pagesetup, content) {
    content = typeof content !== 'undefined' ? content : $("#display");
    $.get('content/' + page, function(data) {
        content.html(data);
        pagesetup();
    });
}


//Events---------->

function adminPage() {

    //LOADS THE DDL MENU INTO THE PAGE
    (function() {
        $.get('core.php?a=getmenu', function(data) {
            $("#menuparent").html($.parseJSON(data));
        });
    }());

    //event handel to create menu
    $("#menubtn").click(function(event) {
        event.preventDefault();

        var menuparent = $("#menuparent").val();
        var menuname = $("#menuname").val();

        /*$.get('core.php?a=createmenu&&parent=' + menuparent + '&&name=' + menuname, function(data) {
         $("#notice").html($.parseJSON(data));
         });*/

        $.ajax({
            type: "POST",
            url: "core.php?a=createmenu&&parent=" + menuparent + "&&name=" + menuname,
            dataType: "json",
            cache: false,
            contentType: "application/json; charset=utf-8",
            success: function(data) {
                alert(data.notification);

            },
            error: function(x, status, error) {
                alert(status + ": " + error);
            }

        });
        adminPage();

    });
}


function addressPage() {
    $("#addressbtn").click(function(event) {
        event.preventDefault();
        base.address = $("#addresstxt").val();
        //todo: validation
        loadContent('landlord.html', landlordPage);
    });
}

function landlordPage() {
    $("#landlordbtn").click(function(event) {
        event.preventDefault();
        base.landlord = $("#landlordtxt").val();
        loadContent('sections.html', sectionsPage);
    });
}



function sectionsPage() {
    function createSections(sectionType, source) {
        var html = "";
        var createBtn = "createSectionBtn";

        if (sectionType === "sub")
            createBtn = "createSubBtn";

        for (var key in source) {

            html += '<li class="btn btn-default _section" data-sectiontype="" data-menuvalue="' + key + '">\n\
                        <a href="javascript:void(0)">\n\
                            <span class="glyphicon glyphicon-list"></span>\n\
                            <span class="BtnTitle">' + source[key].name + '</span>\n\
                        </a>\n\
                     </li>';
        }
        html += '<li class="btn btn-default" id="' + createBtn + '"><a href="javascript:void(0)"><span class="glyphicon glyphicon-list"></span><span class="BtnTitle">Add Section</span></a></li>';
        $(".NavLevelOne").html(html);
        setHandlers();
    }

    function setHandlers() {

        $("._section").click(function(event) {
            event.preventDefault();
          
            selected_section = $(this).attr("data-menuvalue");
            tagtitle += sections[selected_section].name + " &#8594; ";
            parent_section=sections[selected_section];
            console.log(parent_section);
            sections = sections[selected_section].child;
            createSections("top", sections);

            /*if ((sections[selected_section].child).length) {
             sections = sections[selected_section].child;
             createSections("top", sections);
             }
             else
             loadContent(tagprefix + 'tag.html', tagPage); */
        });

        $("#createSectionBtn").click(function(event) {
            event.preventDefault();
            loadContent('createsection.html', createSectionPage);
        });

        $("#backbtn").click(function(event) {
            sectionsPage();
        });

        $("#adddata").click(function(event) {
             //console.log(sections);
            loadContent(tagprefix + 'tag.html', tagPage);
        });

        $("#finishbtn").click(function(event) {
            event.preventDefault();
            loadContent('email.html', sendReportPage);
        });

    }

    tagtitle = "";
    
    sections = dataset;
    //parent_section=sections;
    createSections("top", sections);
}

function createSectionPage() {

    $("#createSectionBtn").click(function(event) {
        event.preventDefault();
        sections.push({});
        sections[sections.length - 1].name = $("#sectiontxt").val();
        sections[sections.length - 1].child = [];
        loadContent('sections.html', sectionsPage);
    });

    $("#cancelBtn").click(function(event) {
        event.preventDefault();
        loadContent('sections.html', sectionsPage);
    });
}

/*function createSubPage() {
 $("#createSubBtn").click(function(event) {
 event.preventDefault();
 sections[selected_section].push($("#subtxt").val());
 loadContent('sections.html', sectionsPage);
 });
 
 $("#cancelBtn").click(function(event) {
 event.preventDefault();
 loadContent('sections.html', sectionsPage);
 });
 }*/

function tagPage() {

    (function() {
        function sendFile(file) {
            var fd = new FormData();
            fd.append("imgfile", file);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'core.php?a=fu', true);

            xhr.onload = function() {
                if (this.status === 200) {
                    var resp = JSON.parse(this.response);
                    addImageCombo(resp.id, resp.url);
                }
            };
            xhr.send(fd);
        }
    }());

    (function() {
        function addImageCombo(id, url) {
            $("#photocontainer").append('<img id="img_' + id + '" src="' + url + '" style="max-width: 70%;margin-top:10px" class="imgattachment" />');
            $("#photocontainer").append('<button id="btn_' + id + '" class="btn btn-danger" data-imgid="' + id + '" style="width:50%;margin-top:10px" >Delete</button>');
            $("#btn_" + id).click(function(event) {
                event.preventDefault();
                $("#img_" + $(this).attr("data-imgid")).remove();
                $(this).remove();
            });
        }
    }());


    function loadData() {

        //var d = sections;
        
        
        if (parent_section.condition)
            $("#conditiontxt").val(parent_section.condition);

        if (parent_section.tags) {
            var tags = parent_section.tags.split(",");
            for (var i = 0; i < tags.length; i++) {
                $("#tags").addTag(tags[i]);
            }
        }

        if (parent_section.images) {
            var images = parent_section.images.split(",");
            if (images[0] !== "") {
                for (var i = 0; i < images.length; i++) {
                    var url = images[i];
                    var id = url.split("/")[1].split(".")[0];
                    addImageCombo(id, url);
                }
            }
        }
    }

    $("#tagtitle").html("&#39;" + tagtitle + "&#39; Tags");
    $("#tags").resetTagPlugin();

    $('#tags').tagsInput();
    loadData();
    if (tagprefix === "") {
        $(".captureuploader").on("tap", function() {
            $('input.captureinput[type=file]').click();
        });

        $('.captureinput').change(function() {
            var file = this.files[0];
            sendFile(file);
        });
    }
    else {
        $(document).on("change", "#fileupload", function() {
            var file = document.getElementById("fileupload").files[0];
            sendFile(file);
        });
    }


    $("#savebtn").click(function(event) {
        event.preventDefault();
        var imgs = "";
        $(".imgattachment").each(function(index, value) {
            imgs += $(value).attr("src") + ",";
        });
        if (imgs.indexOf(","))
            imgs = imgs.substring(0, imgs.length - 1);
       
       
        parent_section.tags = $("#tags").val();
        parent_section.images = imgs,
        parent_section.conditioin = $("#conditiontxt").val();
        
        
        console.log(dataset);
        loadContent('sections.html', sectionsPage);
    });

    $(".conditionbtn").click(function(event) {
        event.preventDefault();
        $("#conditiontxt").val($(this).html());
        $(".conditioncontainer").remove();
    });

    $("#backbtn").click(function(event) {
        event.preventDefault();
        loadContent('sections.html', sectionsPage);
    });

    $("#finishbtn").click(function(event) {
        event.preventDefault();
        loadContent('email.html', sendReportPage);
    }); 

}

function sendReportPage() {
    $("#sendbtn").click(function(event) {
        event.preventDefault();

        base.recipient = $("#emailtxt").val();
        data.base = base;
        data.set = dataset;

        $.ajax({
            type: "POST",
            url: "core.php?a=fr",
            dataType: "json",
            cache: false,
            data: JSON.stringify(data),
            contentType: "application/json; charset=utf-8",
            success: function(data) {
                alert(data.msg);
            },
            error: function(x, status, error) {
                alert(status + ": " + error);
            }

        });
    });

    $("#backbtn").click(function(event) {
        event.preventDefault();
        loadContent('sections.html', sectionsPage);
    });
}