<?php
header('Content-type: application/json');
setlocale(LC_TIME, 'fr_FR');
date_default_timezone_set('Europe/Paris');
$formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE);

if(isset($_POST)){
        if(isset($_POST["dateDeb"]) && $_POST["dateDeb"]!=""){
                $dateDebut = $_POST["dateDeb"];
        }
        if(isset($_POST["dateFin"]) && $_POST["dateFin"]!=""){
                $dateFin = $_POST["dateFin"];
        }
        if(isset($_POST["email"]) && $_POST["email"]!=""){
                $email = $_POST["email"];
        }

        $dateDeb = DateTime::createFromFormat('Y-m-d', $dateDebut);
        $dateFn = DateTime::createFromFormat('Y-m-d', $dateFin);
        /* Read the image into the object */
        $im = new Imagick( $_SERVER['DOCUMENT_ROOT'] . '/img/defaut.jpg' ); 
        //$im->setImageFormat("png"); 
        
        /* Make the image a little smaller, maintain aspect ratio */ 
        //$im->thumbnailImage( 200, null );

        /* Clone the current object */ 
        $shadow = $im->clone(); 
        $draw = new ImagickDraw();
        $draw->setFontSize( 14 );
        $draw->setFillColor("rgb(255, 255, 255)");
        //$draw->setTextDecoration(Imagick::DECORATION_UNDERLINE);
        $draw->setFont('poppins.ttf');
        /* Set image background color to black 
                (this is the color of the shadow) */ 
        //$shadow->setImageBackgroundColor( new ImagickPixel( 'white' ) ); 
        //$shadow->annotateImage($draw, 0, 0, 0, 'The quick brown fox jumps over the lazy dog');
        $im->annotateImage($draw, 40, 39, 0, mb_strtoupper($formatter->format($dateDeb),'UTF-8'));
        $im->annotateImage($draw, 40, 53, 0, mb_strtoupper($formatter->format($dateFn),'UTF-8'));
        
        /* Create the shadow */ 
        //$shadow->shadowImage( 80, 3, 5, 5 ); 

        /* Imagick::shadowImage only creates the shadow. 
                That is why the original image is composited over it */ 
        $shadow->compositeImage( $im, Imagick::COMPOSITE_OVER, 0, 0 ); 

        /* Display the image */ 
        $shadow->writeImage($_SERVER['DOCUMENT_ROOT'] . '/img/'.$email.$dateDebut.$dateFin.'.png');
        /* _-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*/
        /* _-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_FONCTION MAIL-_-_-_-_-_-_-_-_-_--_-_-_-_-_-_-_-_-_-_-*/
        /* _-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*/
        $to = $email; 
        $from = 'si@valoxy.fr'; 
        $fromName = 'SignMaker'; 
        $subject = 'Voici votre signature';  
        $file = "./img/".$email.$dateDebut.$dateFin.".png"; 
        $htmlContent = '<h3>Voici la signature genérée automatiquement, bonnes vacances !!</h3>'; 
        $headers = "From: $fromName"." <".$from.">"; 
        $semi_rand = md5(time());  
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";   
        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";  
        $message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $htmlContent . "\n\n";  

        if(!empty($file) > 0){ 
                if(is_file($file)){ 
                        $message .= "--{$mime_boundary}\n"; 
                        $fp =    @fopen($file,"rb"); 
                        $data =  @fread($fp,filesize($file)); 
                
                        @fclose($fp); 
                        $data = chunk_split(base64_encode($data)); 
                        $message .= "Content-Type: application/octet-stream; name=\"".basename($file)."\"\n" .  
                        "Content-Description: ".basename($file)."\n" . 
                        "Content-Disposition: attachment;\n" . " filename=\"".basename($file)."\"; size=".filesize($file).";\n" .  
                        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n"; 
                 } 
        } 
        $message .= "--{$mime_boundary}--"; 
        $returnpath = "-f" . $from; 
        
        // Send email 
        $mail = @mail($to, $subject, $message, $headers, $returnpath);  

        /* _-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*/
        /* _-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_RETOUR CONTROLLER-_-_-_-_-_-_-_-_-_--_-_-_-_-_-_-_-_-_-_-*/
        /* _-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-_-*/

        echo $mail?json_encode("Le mail a bien été envoyé!"):json_encode("Erreur dans l'envoi du mail."); 
}else{
        echo json_encode("Erreur dans les paramètres !");
}


        
 ?>


