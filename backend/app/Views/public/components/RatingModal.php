<div class="modal fade rating-modal" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-violet-500 text-white">
                <h5 class="modal-title" id="staticBackdropLabel">Adja le értékelését</h5>
            </div>
            <div class="modal-body py-5">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <form>
                                <p class="mb-2">A véleménye fontos számunkra és segít a fejlesztésben. Kérjük, válasszon egy stílust <span class="fw-bold">Ha nem szeretné most elküldeni véleményét, hagyja üresen és küldje el választás nélkül</span> </p>

                                <div class="d-flex flex-wrap align-items-center justify-content-center my-5">
                                    <!-- Smileys (rossztól a jóig) -->
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="feedback" id="style1" value="1">
                                        <label class="form-check-label smiley-label" for="style1">
                                            <span class="smiley-icon" role="img" aria-label="Very Dissatisfied">😞</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="feedback" id="style2" value="2">
                                        <label class="form-check-label smiley-label" for="style2">
                                            <span class="smiley-icon" role="img" aria-label="Dissatisfied">😐</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="feedback" id="style3" value="3">
                                        <label class="form-check-label smiley-label" for="style3">
                                            <span class="smiley-icon" role="img" aria-label="Neutral">🙂</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="feedback" id="style4" value="4">
                                        <label class="form-check-label smiley-label" for="style4">
                                            <span class="smiley-icon" role="img" aria-label="Satisfied">😊</span>
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input visually-hidden" type="radio" name="feedback" id="style5" value="5">
                                        <label class="form-check-label smiley-label" for="style5">
                                            <span class="smiley-icon" role="img" aria-label="Very Satisfied">😄</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="form-group mt-3">
                                    <label for="additionalFeedback">Opcionális visszajelzés:</label>
                                    <textarea class="form-control" name="content" id="additionalFeedback" rows="3" placeholder="Írja le a véleményét, ha szeretné"></textarea>
                                </div>

                                <div class="text-center mt-3">
                                    <button type="submit" class="btn bg-violet-500 hover-bg-violet-600 text-white" id="submitFeedbackBtn">Elküldés</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
