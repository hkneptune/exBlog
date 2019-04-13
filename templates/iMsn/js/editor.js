helpstat = false;
stprompt = true;
basic = false;

function checklength(theform) {
  theform.conlength.value = theform.content.value.length;
}

function thelp(swtch){
if (swtch == 1){
	basic = false;
	stprompt = false;
	helpstat = true;
	} else if (swtch == 0) {
		helpstat = false;
		stprompt = false;
		basic = true;
	} else if (swtch == 2) {
		helpstat = false;
		basic = false;
		stprompt = true;
	}
}

function AddText(NewCode) {

        if(document.all){
        	insertAtCaret(document.exEdit.content, NewCode);
        	setfocus();
        } else{
        	document.exEdit.content.value += NewCode;
        	setfocus();
        }
}

function insertAtCaret (textEl, text){
        if (textEl.createTextRange && textEl.caretPos){
                var caretPos = textEl.caretPos;
                caretPos.text += caretPos.text.charAt(caretPos.text.length - 2) == ' ' ? text + ' ' : text;
        } else if(textEl) {
                textEl.value += text;
        } else {
        	textEl.value = text;
        }
}

function storeCaret (textEl){
        if(textEl.createTextRange){
                textEl.caretPos = document.selection.createRange().duplicate();
        }
}

function setfocus() {
        document.exEdit.content.focus();
}
