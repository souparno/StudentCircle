<?php

require('pdf.php');
require ('pdf_report.php');
require('class.phpmailer.php');
require('class.menu.php');

switch ($_GET["a"]) {


    case "getmenu":

        $arr = json_decode(file_get_contents("menus.txt"), true);
        menu::show_array($arr);
        echo json_encode("<option value='0'>NO PARENT</option>" . menu::$menu_option);


        break;
    case "createmenu":

        $menuparent = $_GET['parent'];
        $menuname = $_GET['name'];
        $arr = json_decode(file_get_contents("menus.txt"), true);


        if ($menuparent):
            menu::put_to_array($arr, $menuparent, array(
                'name' => $menuname,
                'child' => array()
            ));
        else:
            menu::put_to_array_top($arr, array(
                'name' => $menuname,
                'child' => array()
            ));
        endif;

        if (file_put_contents("menus.txt", json_encode($arr))) {
            echo json_encode(
                    array(
                        'notification'=>'menu created successfully'
                    )
                    );
        }


        break;
    case "fa":
        $lines = explode("\n", file_get_contents("menus.txt"));
        echo file_get_contents("menus.txt");
        break;
    case "fu":
        $fileName = $_FILES['imgfile']['name'];
        $fileType = $_FILES['imgfile']['type'];
        //$fileContent = file_get_contents($_FILES['imgfile']['tmp_name']);
        $imgid = str_replace(".", "", microtime(true));
        $newname = $imgid . '.jpg';
        move_uploaded_file($_FILES["imgfile"]["tmp_name"], "files/" . $newname);

        $json = json_encode(array(
            'id' => $imgid,
            'url' => "files/" . $newname
        ));

        echo $json;
        break;
    case "fr":

        $data = json_decode(file_get_contents('php://input'));
        if ($data == null) {
            ob_clean();
            echo '{"msg":"Error: No data received from client"}';
            exit();
        }


        $landlord_name = $data->base->landlord;
        $landlord_address = $data->base->address;
        $recipient = $data->base->recipient;


        $pdfname = get_pdf_report($landlord_name, $landlord_address, $data->set);

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Port = 25;
        $mail->Host = "mail.nisclient.com";
        $mail->From = "noreply@nisclient.com";
        $mail->FromName = "StudentCircle Lettings";
        $mail->AddAddress("souparno.majumder@gmail.com");
        $mail->AddAddress($recipient);
        $mail->Subject = $landlord_address . ' Inventory Report';
        $mail->Body = "Inventory Report\n\nLandlord: " . $landlord_name . "\nAddress: " . $landlord_address . "\n\n\nPlease view the attached PDF for detailed information";
       
        $mail->AddAttachment($pdfname);
        $mail->Username = "noreply@nisclient.com";
        $mail->Password = "@admin2013";
        $mail->Send();
        ob_clean();
        echo '{"msg":"Report Sent!"}';
        break;
}
?>