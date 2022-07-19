<!DOCTYPE html>
<html lang="fr">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Val'Holidays</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <style>
        .red{
            color:red;
        }
        .green{
            color:green;
        }
    </style>
</head>

<body style="background-color:#C1D72E;">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Vous nous enverrez une carte postale 
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-emoji-smile" viewBox="0 0 16 16">
                                            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                            <path d="M4.285 9.567a.5.5 0 0 1 .683.183A3.498 3.498 0 0 0 8 11.5a3.498 3.498 0 0 0 3.032-1.75.5.5 0 1 1 .866.5A4.498 4.498 0 0 1 8 12.5a4.498 4.498 0 0 1-3.898-2.25.5.5 0 0 1 .183-.683zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm4 0c0 .828-.448 1.5-1 1.5s-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5z"/>
                                        </svg>
                                    </h1>
                                    </div>
                                    <form>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Date de début</label>
                                            <input type="date" class="form-control" id="dateDeb" aria-describedby="emailHelp" placeholder="">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputPassword1">Date de fin</label>
                                            <input type="date" class="form-control" id="dateFin">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Adresse mail</label>
                                            <input type="email" class="form-control" id="emailAdress" aria-describedby="emailHelp" placeholder="abcd@gmail.fr">
                                        </div>
                                        
                                    </form>
                                    <button id="submitForm" class="btn btn-primary">Génerer la signature</button>
                                    <p id="result" class="mt-2"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <script>
   jQuery(document).ready(function($) {
    $('#submitForm').click(function() {
        $("#result").removeClass("red");
        $("#result").removeClass("green");
        $("#result").html("");
        let dateDeb =$('#dateDeb').val();
        let dateFin =$('#dateFin').val();
        let email = $('#emailAdress').val();
        if(dateDeb>dateFin){
            alert("La date de debut doit être antérieure à la date de fin");
        }else{
                $.ajax({
            //L'URL de la requête 
            url: "imagick.php",
            //La méthode d'envoi (type de requête)
            method: "POST",
            data : { dateDeb : dateDeb, dateFin : dateFin, email:email },
            //Le format de réponse attendu
            dataType : "json",
            })
            //Ce code sera exécuté en cas de succès - La réponse du serveur est passée à done()
            /*On peut par exemple convertir cette réponse en chaine JSON et insérer
            * cette chaine dans un div id="res"*/
            .done(function(response){
                let data = JSON.stringify(response);
                if(response=="Le mail a bien été envoyé!"){
                    $("#result").html(response);
                    $("#result").addClass("green");
                }else{
                    $("#result").html(response);
                    $("#result").addClass("red");
                }
                
            })

            //Ce code sera exécuté en cas d'échec - L'erreur est passée à fail()
            //On peut afficher les informations relatives à la requête et à l'erreur
            .fail(function(error){
                $("#result").html(error);
                $("#result").addClass("red");
            })

            //Ce code sera exécuté que la requête soit un succès ou un échec
            .always(function(){
            });
    
        } 
    });
    });

</script>

</body>

</html>