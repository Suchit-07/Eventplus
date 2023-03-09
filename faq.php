<?php
require 'init.php';
require 'Database.php';

include_once "navbar.php";

if(!$error){$error = $_GET['error'] ?? null;}
if(!$success){$success = $_GET['success'] ?? null;}
if ($error) {
    echo '<div class="alert alert-danger">' . $error . '</div>';
} elseif($success){
    echo '<div class="alert alert-success">' . $success . '</div>';
}
?>
<div class="p-3 jumbotron text-end">
<button class="btn btn-primary border-2 border-dark" data-bs-toggle="modal" data-bs-target="#addModal">Can't Find The Answer?</button>
</div>
            <input type="hidden" name="action" value="add">
            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h3> Ask Question </h3>
                <button type="button" onClick="window.location.reload();" class="close btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>


                <div class="modal-body">
                <div class="form-outline mb-4">
                <label class="form-label" for="form3Example3">Question</label>
                <input name="name" type="text" id="form3Example3" class="form-control form-control-md" placeholder="Enter your question" required>

                </div>
                </div>


                <div class="modal-footer">
                <button type="button" onClick="window.location.reload();" class="btn btn-secondary border border-dark" data-bs-dismiss="modal">Close</button>
                <a class="btn btn-primary border border-dark" href="faq.php?success=Successfully%20Added%20Question.%20Please%20Wait%20For%20Admin%20to%20Answer%20Question">Ask Question</a>
                </div>
            </div>
            </div>
            </div>
<div class="mt-5 mb-5 jumbotron text-center">
<h1 class="display-3">Frequently Asked Questions</h1>
</div>

<div class="accordion m-3 mt-5" id="accordionExample">
  <div class="accordion-item border border-top-2 border-left-2 border-right-2 border-dark">
    <h2 class="accordion-header " id="headingOne">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
        <b>How do I log in?</b>
      </button>
    </h2>
    <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <p>You can log in using the <a href="login.php">log in</a> page. Don't have an account? Ask an admin to create one for you.</p>
      </div>
    </div>
  </div>
  <div class="accordion-item border border-top-2 border-left-2 border-right-2 border-dark">
    <h2 class="accordion-header " id="headingTwo">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
      <b>I registered for an event. Why didn't my points update?</b>
      </button>
    </h2>
    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
      <div class="accordion-body">
        <p>Your points will update once an admin confirms your attendance at the event!</p>
      </div>
    </div>
  </div>
  <div class="accordion-item border border-top-2 border-left-2 border-right-2 border-dark">
    <h2 class="accordion-header" id="headingThree">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
      <b>Why can't I pick a prize?</b>
      </button>
    </h2>
    <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
      <div class="accordion-body">
            <p>You can only select a prize if you're chosen as a winner. Winners are chosen every quarter so please be patient.</p>
        </div>
    </div>
  </div>

  <div class="accordion-item border border-top-2 border-left-2 border-right-2 border-dark">
    <h2 class="accordion-header" id="headingFour">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
      <b>Why is everyone anonymous on the leaderboard page?</b>
      </button>
    </h2>
    <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
      <div class="accordion-body">
            <p>Everyone is anonymous on the leaderboard to ensure everyone's safety and security. You can still view your rank to see how you place with your peers!</p>
        </div>
    </div>
  </div>

  <div class="accordion-item border border-top-2 border-left-2 border-right-2 border-dark">
    <h2 class="accordion-header" id="headingFive">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
      <b>How do I register for an event?</b>
      </button>
    </h2>
    <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
      <div class="accordion-body">
            <p>You can register for an event by clicking the <code>Register</code> button for any event.</p>
        </div>
    </div>
  </div>

  <div class="accordion-item border border-top-2 border-left-2 border-right-2 border-dark">
    <h2 class="accordion-header" id="headingSix">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
      <b>How do I unregister for an event?</b>
      </button>
    </h2>
    <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
      <div class="accordion-body">
            <p>You can register for an event by clicking the <code>Unregister</code> button for any event.</p>
        </div>
    </div>
  </div>

  <div class="accordion-item border border-top-2 border-left-2 border-right-2 border-dark">
    <h2 class="accordion-header" id="headingSeven">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
      <b>How do I pick a prize?</b>
      </button>
    </h2>
    <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
      <div class="accordion-body">
            <p>After you've been chosen as a winner, you can pick a prize on the prize page. You can view all the available prizes and their point requirements. After you've picked a prize, you can claim it by going to an admin.</p>
        </div>
    </div>
  </div>


  <div class="accordion-item border border-top-2 border-left-2 border-right-2 border-dark">
    <h2 class="accordion-header" id="headingEight">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
      <b>How do I change my password?</b>
      </button>
    </h2>
    <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#accordionExample">
      <div class="accordion-body">
            <p>After you've logged in, you can change your password <a href="change_password.php"> here</a>. </p>
        </div>
    </div>
  </div>


  <div class="accordion-item border border-top-2 border-left-2 border-right-2 border-dark">
    <h2 class="accordion-header" id="headingNine">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
      <b>Why can't I unregister for an event?</b>
      </button>
    </h2>
    <div id="collapseNine" class="accordion-collapse collapse" aria-labelledby="headingNine" data-bs-parent="#accordionExample">
      <div class="accordion-body">
            <p>If you've been confirmed by an admin for an event, you're prevented from unregistering for that event.</p>
        </div>
    </div>
  </div>
</div>