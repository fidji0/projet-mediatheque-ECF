<?php
    require_once "./controllers/pdoConnexion.php";
    require_once "./controllers/search.php";
?>

<div>
    <form class="form-inline my-2 my-lg-0">
        <div class="form-group">
            <label for="exampleFormControlSelect1">Genre </label>
            <select class="form-control" id="exampleFormControlSelect1">
                <option>1</option>
                <option>2</option>

            </select>
        </div>
        <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form>
</div>