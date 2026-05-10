<?php ob_start(); 
$isEdit = isset($car) && $car;
?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <a href="index.php?action=admin_cars" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fa-solid fa-arrow-left me-2"></i> Retour à la Flotte</a>
        <h2 class="fw-bold mb-1"><?= $isEdit ? 'Modifier le Véhicule' : 'Ajouter un Nouveau Véhicule' ?></h2>
        <p class="text-muted">Remplissez les détails ci-dessous pour mettre à jour la flotte.</p>
    </div>
</div>

<?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success rounded-4 border-0 mb-4"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger rounded-4 border-0 mb-4"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-5">
            <form action="index.php?action=admin_car_save" method="POST" enctype="multipart/form-data">
                <?php if($isEdit): ?>
                    <input type="hidden" name="id" value="<?= $car['id'] ?>">
                <?php endif; ?>
                
                <h5 class="fw-bold mb-4">Informations Générales</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Immatriculation</label>
                        <input type="text" name="immatriculation" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['immatriculation']) : '' ?>" placeholder="Ex: DK-1234-AB" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Catégorie</label>
                        <select name="category_id" class="form-select form-select-lg bg-light border-0 rounded-pill px-4" required>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($isEdit && $car['category_id'] == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Marque</label>
                        <input type="text" name="marque" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['marque']) : '' ?>" placeholder="Ex: Mercedes" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Modèle</label>
                        <input type="text" name="modele" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['modele']) : '' ?>" placeholder="Ex: Classe C" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-muted small ms-2">Sélectionnez le Logo de la Marque</label>
                        <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded-4 border">
                            <?php foreach($available_logos as $logo): ?>
                                <div class="brand-logo-item text-center cursor-pointer" onclick="selectLogo('<?= $logo['path'] ?>', this, '<?= $logo['name'] ?>')" style="width: 80px; transition: 0.2s;">
                                    <div class="logo-box bg-white rounded-3 p-2 mb-1 border <?= ($isEdit && $car['brand_logo'] == $logo['path']) ? 'border-warning shadow-sm' : '' ?>" style="height: 60px; display: flex; align-items: center; justify-content: center;">
                                        <img src="<?= $logo['path'] ?>" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                    </div>
                                    <small class="text-muted" style="font-size: 0.7rem;"><?= $logo['name'] ?></small>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="brand_logo_path" id="selected_brand_logo" value="<?= $isEdit ? htmlspecialchars($car['brand_logo']) : '' ?>">
                        
                        <div class="mt-3">
                            <label class="form-label fw-bold text-muted small ms-2">Ou uploader un logo personnalisé</label>
                            <input type="file" name="brand_logo_file" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" accept="image/*">
                        </div>
                    </div>
                </div>

                <script>
                function selectLogo(path, element, brandName) {
                    document.getElementById('selected_brand_logo').value = path;
                    document.querySelector('input[name="marque"]').value = brandName;
                    document.querySelectorAll('.logo-box').forEach(box => box.classList.remove('border-warning', 'shadow-sm'));
                    element.querySelector('.logo-box').classList.add('border-warning', 'shadow-sm');
                }
                </script>
                <style>
                .brand-logo-item { cursor: pointer; }
                .brand-logo-item:hover .logo-box { border-color: #f4c053; transform: translateY(-2px); }
                .logo-box { transition: 0.2s; }
                </style>

                <h5 class="fw-bold mb-4">Spécifications Techniques</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small ms-2">Carburant</label>
                        <select name="type_carburant" class="form-select form-select-lg bg-light border-0 rounded-pill px-4">
                            <?php $fuels = ['Essence', 'Diesel', 'Electrique', 'Hybride']; foreach($fuels as $f): ?>
                                <option value="<?= $f ?>" <?= ($isEdit && $car['type_carburant'] == $f) ? 'selected' : '' ?>><?= $f ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small ms-2">Boîte de vitesse</label>
                        <select name="boite_vitesse" class="form-select form-select-lg bg-light border-0 rounded-pill px-4">
                            <option value="Manuelle" <?= ($isEdit && $car['boite_vitesse'] == 'Manuelle') ? 'selected' : '' ?>>Manuelle</option>
                            <option value="Automatique" <?= ($isEdit && $car['boite_vitesse'] == 'Automatique') ? 'selected' : '' ?>>Automatique</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small ms-2">Nb de Places</label>
                        <input type="number" name="nb_places" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['nb_places']) : '5' ?>">
                    </div>
                </div>

                <h5 class="fw-bold mb-4">Tarification & Photo</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Prix Journalier (FCFA)</label>
                        <input type="number" name="prix_journalier" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['prix_journalier']) : '' ?>" placeholder="Ex: 25000" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Caution / Garantie (FCFA)</label>
                        <input type="number" name="caution" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['caution']) : '0' ?>" placeholder="Ex: 500000">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Photo du Véhicule (Fichier)</label>
                        <input type="file" name="image_file" class="form-control form-control-lg bg-light border-0 rounded-pill px-4 mb-2" accept="image/*">
                        <small class="text-muted ms-2">Ou URL actuelle :</small>
                        <input type="text" name="image_principale" class="form-control bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['image_principale']) : '' ?>" placeholder="URL de l'image (optionnel)">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Date Prochaine Assurance</label>
                        <input type="date" name="date_assurance" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['date_assurance'] ?? '') : '' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Galerie Photos (Plusieurs fichiers)</label>
                        <input type="file" name="gallery_files[]" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" accept="image/*" multiple>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Statut Actuel</label>
                        <select name="status" class="form-select form-select-lg bg-light border-0 rounded-pill px-4">
                            <option value="disponible" <?= ($isEdit && $car['status'] == 'disponible') ? 'selected' : '' ?>>Disponible</option>
                            <option value="maintenance" <?= ($isEdit && $car['status'] == 'maintenance') ? 'selected' : '' ?>>En Maintenance</option>
                            <option value="louée" <?= ($isEdit && $car['status'] == 'louée') ? 'selected' : '' ?>>Louée (Occupé)</option>
                        </select>
                    </div>
                </div>

                <?php if($isEdit && !empty($gallery)): ?>
                <h5 class="fw-bold mb-4">Galerie Actuelle</h5>
                <div class="row g-3 mb-5">
                    <?php foreach($gallery as $img): ?>
                    <div class="col-md-3">
                        <div class="position-relative">
                            <img src="<?= htmlspecialchars($img['image_path']) ?>" class="img-fluid rounded-3 border" style="height: 120px; width: 100%; object-fit: cover;">
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="text-end">
                    <a href="index.php?action=admin_cars" class="btn btn-light btn-lg rounded-pill px-5 fw-bold me-2">Annuler</a>
                    <button type="submit" class="btn btn-dark btn-lg rounded-pill px-5 fw-bold"><?= $isEdit ? 'Enregistrer les Modifications' : 'Ajouter le Véhicule' ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>