'use strict';
(function(){
	var htmlElements = {
		body: document.querySelector('body'),
		header: document.querySelector('header'),
		forms: document.querySelectorAll("form"),
		allDeskNumbers : document.querySelectorAll (".deskvsemployee .desk span"),
		allDesk : document.querySelectorAll (".deskvsemployee "),
		currentdate: document.querySelector("#currentdate"),
		mainEmployeeNum: document.querySelector("#maindeskvsemployee .desk span"),
		changefixedImg: document.querySelector("#changefixed img"),
		fixedDeskStatus: document.querySelector("#select_user form label"),

	};

	var app = {
		init: function() {
			handleForms.addSubmitListeners();
			util.checkResultAndAdClass();
			
		}
	};

	var handleForms = {
		addSubmitListeners : function () {
			var formsLength = htmlElements.forms.length;
			for (var i = 0; i < formsLength; i++) {
				htmlElements.forms[i].addEventListener("submit",handleForms.processForm)
			};
		},
		processForm : function (e) {
			e.preventDefault();
			
			var that=this;
			var req = new XMLHttpRequest();
			var FD  = new FormData(this);

			req.open("POST", "index.php");
			req.onreadystatechange = function (Evt) {
				if (req.readyState == 4) {
					if(req.status == 200) {
					  	handleForms.checkWichForm(that);
						
					} else
					  alert("Error loading page\n");
				}
			};
			req.send(FD);

			return false;
		},
		checkWichForm : function (form) {
			if (form.innerHTML.indexOf("changeGoingOffice")>-1) {// going or not going to office
				handleForms.changeCalState(form);
			} 
			else if (form.innerHTML.indexOf("changeFixed")>-1) {// fixedDesk
				handleForms.changeFixedState(form);
			}
			else if (form.innerHTML.indexOf("selectuser")>-1) {// choose user
				handleForms.chooseUser(form);
			};	
		},
		changeCalState :function(form) {

			var plusOrMin = 0;
 
			var buttonIndex = util.findElement (form,"button");
			var imgIndex = util.findElement(form.children[buttonIndex],"img");

			if (util.hasClass(form.parentElement,"emptydesk")) { //going to work
				plusOrMin = -1;
				form.parentElement.classList.remove("emptydesk");
				
			} else {// change to not going to work
				plusOrMin=1;
				form.parentElement.classList.add("emptydesk");
				
			}
			
			
			var element = document.querySelector('#'+ form.parentElement.id +' .desk span');
			var currentNumber2 = Number(element.innerHTML);
			
			currentNumber2+=plusOrMin;
			

			element.innerHTML = currentNumber2; 
					
		},
		checkIfFixed : function () {
			if (htmlElements.fixedDeskStatus.innerHTML.indexOf("Not")> -1) {
				handleForms.removeDeskFromAll(-1);
				return false;
			}
			else {
				handleForms.removeDeskFromAll(1);
				return true;
			}
		},
		removeDeskFromAll : function (plusOrMin) {
			for (var i = 0; i < htmlElements.allDeskNumbers.length; i++) {
				var newNumber = Number(htmlElements.allDeskNumbers[i].innerHTML)
				newNumber+=plusOrMin;
				htmlElements.allDeskNumbers[i].innerHTML = newNumber;
			};
		},
		changeFixedState : function (form) {
			
			if (!handleForms.checkIfFixed()) {

				htmlElements.fixedDeskStatus.innerHTML= "Fixed";
				htmlElements.changefixedImg.src = "images/desklock.svg";
			}
			else {
				htmlElements.fixedDeskStatus.innerHTML= "Not fixed";
				htmlElements.changefixedImg.src = "images/deskunlock.svg";
			}
		},
		chooseUser:function(form) {
			
			location.reload();
		}

	}
	var util = {
		hasClass: function (element, cls) {
		    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
		},
		findElement : function (element,searchthis) {
			var element = element.children;
			for (var i = 0; i < element.length; i++) {
				if (element[i].localName.indexOf(searchthis)>-1) {
					return i;
				};
				
			};
		},
		checkResultAndAdClass : function () {
			for (var i = htmlElements.allDeskNumbers.length - 1; i >= 0; i--) {
				if (Number(htmlElements.allDeskNumbers[i].innerHTML)>-1) {
					htmlElements.allDesk[i].classList.remove("negative");
					htmlElements.allDesk[i].classList.add("positive");
				} else {
					htmlElements.allDesk[i].classList.add("negative");
					htmlElements.allDesk[i].classList.remove("positive");
				}
				
			}
		}
	}
	app.init();
})();