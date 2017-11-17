<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <form action="actions/addObject.php" method="post">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="myModalLabel">Une nouvelle idée ?</h3>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 col-md-10 col-md-offset-1">
                            <input type="hidden" name="user_id" value="<?php echo $currentUser['id']; ?>">

                            <div class="form-group">
                                <label for="nom">Nom *</label>
                                <input type="text" class="form-control" id="nom" name="nom" placeholder="nom" required="required">
                            </div>

                            <div class="form-group">
                                <label for="description">Description *</label>
                                <textarea class="form-control" id="description" name="description" placeholder="Description" required="required"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="image">Lien vers l'image</label>
                                <input type="text" class="form-control" id="image" name="image" placeholder="Lien vers l'image (optionnel)">
                            </div>

                            <div class="form-group">
                                <label for="link">Lien vers l'objet</label>
                                <input type="text" class="form-control" id="link" name="link" placeholder="Lien vers l'objet (optionnel)">
                            </div>

                            <p>* champs obligatoires</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                    <input type="submit" class="btn btn-primary" value="Ajouter">
                </div>
            </div>
        </form>
    </div>
</div>
