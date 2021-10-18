function Afficher()
{ 
    var input = document.getElementById("afPassword"); 
    
    if (input.type === "password"){ 
        input.type = "text"; 
    }else{ 
        input.type = "password"; 
    } 
} 
function RedirectionUser(){
    document.location.href="./connectedUser.php"; 
  }
function RedirectionEmployer(){
    document.location.href="./connectedUser.php"; 
}
function NotConnected(){
    document.location.href="./connect.php"; 
}