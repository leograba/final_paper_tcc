function headerPHP(pathToHeader){//create the header of the page
    $.post(pathToHeader, {url:document.URL} , function(data, status){//send the page URL to PHP
        if(status == "success"){//if POST is successfull
            $('body').prepend($(data));//add the received header to the top of the page
            $('body').show();//and displays the hidden page afterwards
        }
    });
}