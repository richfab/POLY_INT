<h2><span class="glyphicon glyphicon-ok"></span> Email d'activation</h2>
<div class="well">
    <h4>
        Tu dois d'abord activer ton compte à l'aide du lien d'activation qui t'a été envoyé par email (cela peut prendre un peu de temps).
    </h4>
    <p>
        <?php echo $this->Html->link("Renvoyer l'email", array("controller" => "users", "action" => "resend_activation_email", $email_at_signup, true)); ?>
    </p>
</div>