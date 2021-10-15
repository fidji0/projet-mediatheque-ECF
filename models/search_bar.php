<div>
    <nav class="navbar navbar-light bg-light">
        <form class="form-inline my-2 my-lg-0 flexboxSearch" method="get">
        
            
                <div>
                
                    <select class="form-control" name="genre" id="exampleFormControlSelect1">
                        <option value="" >Genre</option>
                        <?php
                            $search->genre($pdo);
                        ?>

                    </select>
                </div>
            
            <div>
                <input class="form-control mr-sm-2" name="title" type="search" placeholder="Titre" aria-label="Search">
            </div>
            <div>
                <button class="btn btn-primary my-2 my-sm-0" type="submit" name="search_book" value="1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"></path>
                    </svg></button>
            </div>
        </form>

    </nav>
</div>