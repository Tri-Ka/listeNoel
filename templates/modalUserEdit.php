<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal">
    <div class="modal-dialog" role="document">
        <form action="actions/editUser.php" method="post" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="myModalLabel">Modifier</h3>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-8 col-md-offset-2">
                            <div class="form-group">
                                <label for="nom">Ton nom *</label>
                                <input type="text" class="form-control" id="nom" name="nom" placeholder="nom" required="required" value="<?php echo $currentUser['nom']; ?>">
                            </div>

                            <div class="form-group">
                                <label for="description">Photo *</label>
                                <input type="file" class="form-control" id="pictureFile" name="pictureFile" placeholder="fichier" required="required">
                            </div>

                            <div class="form-group">
                                <label for="description">Mot de passe *</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe" required="required">
                            </div>

                            <div class="form-group">
                                <label for="description">Répeter le mot de passe *</label>
                                <input type="password" class="form-control" id="re-password" name="re-password" placeholder="Répeter le mot de passe" required="required">
                            </div>

                            <p>* champs obligatoires</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="text-right">
                        <button type="button" class="btn btn-default full-xs" data-dismiss="modal">Annuler</button>
                        <input type="submit" class="btn btn-primary full-xs" value="Modifier">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
