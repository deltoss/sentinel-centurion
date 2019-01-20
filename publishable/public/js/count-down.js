/*
    By Michael Tran
    Converts a DOM element to a CountDown timer that ticks every second, 
    which invokes a function once it hits 0.
    Useful for places where it redirects users to another page in X seconds.

    Versioning
    ----------------------------------
    | Version  |    Modified By    |
    | 1.0      |    Michael Tran   |
    | 1.1      |   Aaron O'Donnell |
    | 1.2      |    Michael Tran   |
    ----------------------------------

    Current Version: 1.2
*/

//Main count down timer function used to give a count down effect for an element
//Usage: 
//  countDown(document.getElementById("timer"), function(){ ...YourCode }, seconds);
//  countDown(document.getElementById("timer"), 'Homepage.html', seconds);
//Does not accept JQuery elements, however can be modified to convert JQuery elements to DOM elements.
//seconds are optional integer values.
//Alternatively, you can pass in the element with the value of seconds already inside it.
//baseFunction is optional parameters which is the function that's called once the timer hits 0;
function countDown(element, baseFunctionOrUrl, seconds)
{
	if (typeof baseFunctionOrUrl == 'function')
		countDownWithFunction(element, baseFunctionOrUrl, seconds);
	else if (typeof baseFunctionOrUrl == 'string')
		countDownRedirect(element, baseFunctionOrUrl, seconds);
	else 
		console.error("Must pass in a URL string, or a callback function into the second parameter of countDown function.");
}

function countDownWithFunction(element, baseFunction, seconds) {
	//default values
    baseFunction = baseFunction || function () { };
    seconds = seconds || null;
    if (seconds === null) {
        seconds = parseInt(element.innerHTML);
        if (isNaN(seconds))
            seconds = 10;  //Setting the default seconds to 10
    }
    decrementElementText(element, baseFunction, seconds + 1);
}

//Convert an element to a count down timer
//Does not do error checking to check whether element has number text, nor set default values.
function decrementElementText(element, baseFunction, seconds) {
    //Decrements an element with a number
    if (seconds === 1) {
        window.setTimeout(baseFunction, 1000);
    }
    else {
        window.setTimeout(function () {
            seconds = seconds - 1;
            decrementElementText(element, baseFunction, seconds);
        }, 1000);
    }
    element.innerHTML = seconds - 1;
}

//element - The javascript element which contains the timer to decrement (usually a span with a number in it)
//redirectUrl - Where you are redirecting the user to after the timer 
//seconds - Optional. Number of seconds to count down from. If your element already has a number.
function countDownRedirect(element, redirectUrl, seconds) {
	var callBackFunction = function () {
		window.location.href = redirectUrl;
	}
	countDown(element, callBackFunction, seconds);
}