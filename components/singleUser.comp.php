    <?php
    //get User info from session;
    // using $Currentuser

    ?>


    <div class="mb-2 mt-5">
        <div class="row g-0">
            <div class="col-md-4  d-flex align-content-center">


                <img class="card-img user-Img cover " src="//<?php echo ($CurrentUser->IMG); ?>" width="50">
            </div>
            <div class="col-md-8">
                <div class="card-body d-flex flex-row flex-wrap h-100">
                    <div class="row d-flex align-items-center  ">
                        <div class="col">


                            <h5 class="card-title"><?php echo ($CurrentUser->UserName) ?></h5>
                            <small>
                                status: <?php echo ($CurrentUser->status) ?>
                            </small>

                            <div class="row">
                                <div class="col">
                                    <p>
                                        <?php echo ($CurrentUser->Anrede) ?>
                                    </p>
                                </div>
                                <div class="col">
                                    <?php echo ($CurrentUser->Vorname) ?>
                                    </p>
                                </div>
                                <div class="col">
                                    <?php echo ($CurrentUser->Nachname) ?>
                                    </p>
                                </div>








                            </div>
                            <div class="d-flex flex-row justify-content-end">
                                <div class="Comp-like">

                                    <form action="//<?php echo (DIR_SERVERROOT .  $_SERVER['REQUEST_URI']); ?>">
                                        <input type="hidden" name="edit" value="true" />

                                        <button type="submit" class="btn btn-sm btn-sm-my btn-outline-primary">

                                            edit profile</button></form>
                                </div>
                            </div>

                        </div>



                    </div>
                </div>


            </div>
        </div>
    </div>