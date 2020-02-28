$(document).ready(function(){
    
});

function imprimerRecu(_idcaisse){
    if (!$("input[name=certifier]").is(":checked")) {
        alertWebix("Veuillez certifier avoir recu un tel montant \n \n\
            en votre nom en cochant la case certification");
        addRequiredFields([$(".navigation")]);
        return;
    }
    
    var frm = $("<form>", {
        action: "../imprimer",
        target: "_blank",
        method: "post"
    }).append($("<input>", {
        name: "code",
        type: "hidden",
        value: "0002"
    })).append($("<input>", {
        name: "idcaisse",
        type: "hidden",
        value: _idcaisse
    })).appendTo("body");
    frm.submit();
}