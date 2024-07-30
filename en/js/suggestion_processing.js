/*
 * Isso vai ser sempre depois que a p�gina carregar, � uma "main" s� que n�o
 */
$(function() {

    //Previne o Submit do form ao apertar Enter
    $('form').bind("keypress", function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

    //Quando pressionado Enter no campo de texto da suggestion, insere a nova option
    $('#inputWord').keypress(function(event) {
        if (event.keyCode == 13) {
            addSuggestion();
        }
    });

    //Quando pressionado "D" no campo da selects, deleta a op��o selecionada
    $('#candidateList').keypress(function(event) {
        if (event.keyCode == 100) {
        	removeSelected();
        }
    });

});

function addSuggestion() {

    //busca a nova sugest�o a ser inserida no text input
    var newSuggestion = $('#inputWord').val();
    if(newSuggestion.length != 0) { 
      appendNewOption(newSuggestion); 
    }
    //limpa o input
    $('#inputWord').val('');
}

function appendNewOption(textToNewOption) {
	
	//inicia uma diretriz do tipo option
    var newOption = document.createElement('option');

    //Adiciona o texto na nova option
    //Observa��o, sempre insira o VALUE da tua option, � isso que vai ser enviado
    newOption.text = textToNewOption;
    newOption.value = textToNewOption;

    //apenda a nova option
    $('#candidateList').append(newOption);
}

function removeSelected() {

	//seleciona uma lista de todas as op��es selecionadas
    $('#candidateList :selected').remove();
}

function clearAll() {	
	//seleciona uma lista de todas as op��es existente na select
    $('#candidateList option').remove();
}

function isValidForm(){
    var equiv = document.getElementById('candidateList');
	var nbEquiv = equiv.length;
	if (nbEquiv < 2) {
	  alert("In item 3 you must enter at least 2 synonym suggestions!");
	  return false; // keep form from submitting
	}
	if(equiv[0].textContent.trim() == "" || equiv[1].textContent.trim() == "" ){
	  alert("Synonym suggestions cannot be empty");
	  return false; // keep form from submitting
	}
	if( equiv[0].textContent.trim() == equiv[1].textContent.trim() ){
	  alert("Please enter two DIFFERENT synonyms");
	  return false;
	}

	if( document.getElementById('questio11').checked==false &&
	    document.getElementById('questio12').checked==false &&
	    document.getElementById('questio13').checked==false &&
	    document.getElementById('questio14').checked==false &&
	    document.getElementById('questio15').checked==false &&
	    document.getElementById('questio16').checked==false){
      alert('Todas as perguntas devem ser respondidas');
      return false;
	}

	if( document.getElementById('questio21').checked==false &&
	    document.getElementById('questio22').checked==false &&
	    document.getElementById('questio23').checked==false &&
	    document.getElementById('questio24').checked==false &&
	    document.getElementById('questio25').checked==false &&
	    document.getElementById('questio26').checked==false){
      alert('Todas as perguntas devem ser respondidas');
      return false;
	}
	if( document.getElementById('questio31').checked==false &&
	    document.getElementById('questio32').checked==false &&
	    document.getElementById('questio33').checked==false &&
	    document.getElementById('questio34').checked==false &&
	    document.getElementById('questio35').checked==false &&
	    document.getElementById('questio36').checked==false){
      alert('Todas as perguntas devem ser respondidas');
      return false;
	}
	 // else form is good let it submit, of course you will 
	 // probably want to alert the user WHAT went wrong.

	 return true;
}

function selectAll() {
    var e = document.getElementById('candidateList');
    for(var i=0; i < e.options.length; i++)  {
	  e.getElementsByTagName('option')[i].selected = 'selected';	
    }
}

function checkValid() {
  if( isValidForm() ) {
    selectAll();
	var bn = document.getElementById('bttNext');
	bn.style.display='none';
	var dbn = document.createElement("button");
	dbn.appendChild(document.createTextNode("Sending..."));
	dbn.setAttribute("class","btn btn-default");
	dbn.style.width = "100px";
	dbn.style.float="right";
	dbn.disabled = true;
	dbn.type="submit";
	bn.parentElement.appendChild(dbn);
	var bp = document.getElementById('bttPular');
	bp.style.display='none';	
	var dbp = document.createElement("button");
	dbp.appendChild(document.createTextNode("Sending..."));
	dbp.setAttribute("class","btn btn-default");
	dbp.disabled = true;
	dbp.type="submit";
	bp.parentElement.appendChild(dbp);	
	//e.hide();
    //e.disabled=true; 
    //e.value='Enviando...';    
    return true;
  }
  return false;
}

function setValidoParaPular(){
	var e = document.getElementById('candidateList');
	var opt1 = document.createElement('option');
	var opt2 = document.createElement('option');
	opt1.appendChild(document.createTextNode("Dummy 1"));
	opt2.appendChild(document.createTextNode("Dummy 2"));
	opt1.style.display='none';
	opt2.style.display='none';
	e.innerHTML="";	
	e.appendChild(opt1);
	e.appendChild(opt2);
	document.getElementById('questio11').checked=true;
	document.getElementById('questio21').checked=true;
	document.getElementById('questio31').checked=true;
}
