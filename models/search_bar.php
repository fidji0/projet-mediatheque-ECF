<div>
    <nav class="navbar navbar-light bg-light">
        <form class="form-inline my-2 my-lg-0 flexboxSearch" method="get">
        
            
            <div class="form-group">
                <label for="exampleFormControlSelect1">Genre </label>
            </div>
            <div>
                <select class="form-control" name="genre" id="exampleFormControlSelect1">
                    <option value="" >Choisissez un genre</option>
                    <?php
                        $search->genre($pdo);
                    ?>

                </select>
            </div>
            <div>
                <input class="form-control mr-sm-2" name="title" type="search" placeholder="Titre" aria-label="Search">
            </div>
            <div>
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit" name="search_book" value="1">Rechercher</button>
            </div>
        </form>

    </nav>
</div>