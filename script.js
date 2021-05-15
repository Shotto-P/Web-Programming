function popup(){
    var popupBlock = document.getElementById("popup");
    popupBlock.style.display = "block";
}

function closePopup(){
    var popupBlock = document.getElementById("popup");
    popupBlock.style.display = "none";
}

function popupReason(){
    var popupBlock = document.getElementById("popupReason");
    popupBlock.style.display = "block";
}

function popupReasonClose(){
    var popupBlock = document.getElementById("popupReason");
    popupBlock.style.display = "none";
}

function popupPayment(){
    var popupBlock = document.getElementById("popupPayment");
    popupBlock.style.display = "block";
    var priceTable = document.getElementById("priceTable").innerHTML;
    console.log(priceTable);
    var price=document.getElementById("price");
    price.value=priceTable;
}

function popupPaymentClose(){
    var popupBlock = document.getElementById("popupPayment");
    popupBlock.style.display = "none";
}

function popupReply(){
    var popupBlock = document.getElementById("popupReply");
    popupBlock.style.display = "block";
}

function popupReplyClose(){
    var popupBlock = document.getElementById("popupReply");
    popupBlock.style.display = "none";
}

function validCard() {
	var error=document.getElementById("VISAerror");
	var visa=document.getElementById("visa");
	var patt=/^[0-9]{4}(\s[0-9]{4}){3}$/;
	var check=document.getElementById("card");
	if(!patt.test(check.value)){
		error.innerHTML="This is not a valid Credit Card Number!";
		visa.style.display="none";
	}else{
		visa.style.display="inline-block";
		error.innerHTML="";
	}
}
function popupEdit(){
    var popupBlock = document.getElementById("popupEdit");
    popupBlock.style.display = "block";
}

function popupEditClose(){
    var popupBlock = document.getElementById("popupEdit");
    popupBlock.style.display = "none";
}
