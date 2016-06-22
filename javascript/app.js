'use strict';
(function(){
	var htmlElements = {
		body: document.querySelector('body'),
		header: document.querySelector('header'),
		forms: document.querySelectorAll("form"),
		currentdate: document.querySelector("#currentdate"),
		mainEmployeeNum: document.querySelector("#maindeskvsemployee .employee span"),
		changefixedImg: document.querySelector("#changefixed img"),
		fixedDeskStatus: document.querySelector("#select_user form label")
	};

	var app = {
		init: function() {
			handleForms.addSubmitListeners();
			
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
			req.onreadystatechange = function (aEvt) {
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
			if (util.hasClass(form.parentElement,"emptydesk")) { //going to work
				plusOrMin = 1;
				form.parentElement.classList.remove("emptydesk");
				form.children[1].src="images/deskperson.svg";
			} else {// change to not going to work
				plusOrMin=-1;
				form.parentElement.classList.add("emptydesk");
				form.children[1].src="images/desk.svg";
			}
			if (htmlElements.currentdate.innerHTML.indexOf(form.children[0].innerHTML)>-1) {//main date matches the changed date
				var currentNumber = Number(htmlElements.mainEmployeeNum.innerHTML);
				
				currentNumber+=plusOrMin;
				htmlElements.mainEmployeeNum.innerHTML=currentNumber;
			};
			
			var element = document.querySelector('#'+ form.parentElement.id +' .employee span');
			var currentNumber2 = Number(element.innerHTML);
			currentNumber2+=plusOrMin;

			element.innerHTML = currentNumber2; 
					
		},
		changeFixedState : function (form) {
			
			if (htmlElements.fixedDeskStatus.innerHTML.indexOf("Not")> -1) {

				htmlElements.fixedDeskStatus.innerHTML= "Fixed";
				htmlElements.changefixedImg.src = "images/deskpersonlock.svg";
			}
			else {
				htmlElements.fixedDeskStatus.innerHTML= "Not fixed";
				htmlElements.changefixedImg.src = "images/deskpersonunlock.svg";
			}
		},
		chooseUser:function(form) {
			
			location.reload();
		}

	}
	var util = {
		hasClass: function (element, cls) {
		    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
		}
	}
	app.init();
})();