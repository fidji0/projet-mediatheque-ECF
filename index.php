<?php
session_start();
require_once "./controllers/pdoConnexion.php";

$title = "Bienvenue sur le site de la médiathèque de ...";
$descriptionPage = "Bienvenue sur le site de la médiathèque de ..., nous sommes heureux de votre présence.";


require_once "./models/head.php";
require_once "./models/header.php";
?>
<main>
<div class="flexboxIndex">
    <div>
        <img src="./vues/Img/bibliotheque.jpg" alt="">
    </div>
    <div class="presentation">
        <h2>Bienvenue sur le site de la bibliothèque</h2>

        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Necessitatibus, est dolorum cum optio hic dignissimos totam, quia delectus fuga amet consequuntur temporibus velit exercitationem nisi iste eligendi animi quod quas?
        Animi non voluptates necessitatibus quidem. Doloribus aperiam ad provident reiciendis vero harum veniam est corrupti totam sint quae tempore ipsum minima amet eligendi, pariatur fugit cupiditate ratione maxime modi. Quod!
        Ducimus deserunt qui explicabo beatae vero sapiente corporis blanditiis! Alias ratione ea nihil exercitationem asperiores accusamus explicabo corporis, voluptates odit. Natus, sapiente sed. Cumque temporibus totam quisquam distinctio, ut dignissimos!
        Ad aut sit necessitatibus eaque voluptatum voluptatem corporis libero ducimus ut voluptas eligendi, tenetur assumenda voluptates doloribus recusandae blanditiis sapiente? Qui ducimus, accusamus totam ratione veniam fuga possimus odit esse.
        At dolor officia quos corporis ipsum facere, iste nesciunt eius dicta quia! Natus, dolorem. Porro obcaecati, corporis unde, facere quidem ab dignissimos ullam deserunt mollitia eveniet neque asperiores? Ex, saepe.
        Tenetur, consectetur omnis cumque inventore nemo delectus impedit, aliquam vero ea rem aliquid! Delectus molestiae minus architecto corporis, eos, autem incidunt iusto blanditiis asperiores modi at quasi. Earum, laborum eligendi.
        Consequatur dolorum explicabo accusantium fugiat atque, voluptas sunt. Sit, alias amet voluptatibus ad eius eos, dolor quam quas eum cumque fuga ratione commodi a consectetur natus non esse! Dolorem, culpa.
        Reiciendis, culpa doloribus! Culpa cupiditate animi nihil consequuntur placeat, ex quam atque quia inventore ratione aliquam voluptatem dolor vero! Reprehenderit non cupiditate ratione, unde dignissimos eaque obcaecati repellat officia delectus!
        Dolores deserunt ratione praesentium? Delectus sed sit in suscipit est ratione tenetur voluptate consequuntur nemo quas esse, quod velit nobis impedit eum voluptas vel nostrum error aperiam unde doloribus laudantium.
        Adipisci quos iure a accusantium, quia distinctio cupiditate nisi dolore quas expedita. Saepe possimus debitis quos corrupti quasi, dolore dolorem tempore sit laboriosam quibusdam iusto deleniti doloribus eligendi nulla obcaecati.</p>
    </div>
</div>






</main>
















<?php
var_dump($_SESSION);
require_once "./models/footer.php";
