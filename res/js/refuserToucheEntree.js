function refuserToucheEntree(event)
{
	if(!event && window.event) {
		event = window.event;
	}
	if(event.keyCode == 13) {
		event.returnValue = false;
		event.cancelBubble = true;
	}
	if(event.which == 13) {
		event.preventDefault();
		event.stopPropagation();
	}
}
