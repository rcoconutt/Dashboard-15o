/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function (){
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)), sURLVariables = sPageURL.split('&'), sParameterName,i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };
    
    var tech = getUrlParameter('status');
    if(tech == '1'){
        alert('mostrar error');
    }
});

function validateForm(evt) {
    var user = document.forms["login"]["user"].value;
    var pass = document.forms["login"]["pass"].value;
    
    try {
        if(user == "" || pass == ""){
             alert("Campos obligatorios.");
             return false;
        }
        
    } catch (e) {
     throw new Error(e.message);
    }
}
