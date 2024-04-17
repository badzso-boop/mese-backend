<!doctype html>
<html>
<head>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/filament/turn.min.js"></script>


<style type="text/css">
    body{
        background:#ccc;
    }
    #book{
        width:800px;
        height:500px;
        margin-top: 20px!important;
        margin-bottom: 20px !important;
        margin: auto;
    }

    #book .turn-page{
        background-color:white;
    }

    #book .cover{
        background-image: url('/images/proba.jpeg');
        background-size: cover;
        background-position: center;
        box-shadow: rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset;
    }

    #book .cover h1{
        color: black;
        text-align: center;
        font-size: 45px;
        line-height: 500px; 
        margin:0px;
    }

    #book .cover .bottom-left {
        width: 75px;
        height: 150px;
        position:absolute;
        bottom: 0;
        right: 25px;
        background-image: url('images/IntegetosLany.png');
        background-size: cover;
        background-position: center;
    }

    #book .cover .bottom-right {
        width: 75px;
        height: 150px;
        position:absolute;
        bottom: 0;
        left: 25px;
        background-image: url('images/IntegetosFiu.png');
        background-size: cover;
        background-position: center;
    }

    #book .cover .top-center {
        width: 75px;
        height: 75px;
        position:absolute;
        top: 150px;
        left: 165px;
        background-image: url('images/logo-inverz.png');
        background-size: cover;
        background-position: center;
    }

    #book .loader {
        background-image: url('/images/loader.gif');
        width: 24px;
        height: 24px;
        display: block;
        position: absolute;
        top: 238px;
        left: 188px;
    }

    #book .data{
        padding: 25px;
    }

    #book .data-title{
        padding: 25px;
    }

    #book .data-title h1{
        color: black;
        text-align: center;
        font-size: 40px;
        line-height: 250px;
        margin:0px;
    }

    .page-number-even {
        position: absolute;
        bottom: 10px;
        right: 25px;
        border-top: 2px solid black;
        width: 85%;
        text-align: right;
        padding: 5px;
    }

    .page-number-odd {
        position: absolute;
        bottom: 10px;
        left: 25px;
        border-top: 2px solid black;
        width: 85%;
        padding: 5px;
    }

    .top-small-title {
        position:absolute;
        top: 0;
        left: 25px;
        border-bottom: 2px solid black;
        width: 85%;
        margin-top: 10px;
        padding-bottom: 5px;
    }

    .top-number {
        text-align: right;
        float: right;
    }

    .szoveg {
        font-size: 20px;
    }

    #controls{
        width:800px;
        text-align:center;
        margin:20px 0px;
        font:30px arial;
        margin: auto;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    #controls input, #controls label{
        font:30px arial;
    }

    #book .odd{
        background-image:-webkit-linear-gradient(left, #FFF 95%, #ddd 100%);
        background-image:-moz-linear-gradient(left, #FFF 95%, #ddd 100%);
        background-image:-o-linear-gradient(left, #FFF 95%, #ddd 100%);
        background-image:-ms-linear-gradient(left, #FFF 95%, #ddd 100%);
    }

    #book .even{
        background-image:-webkit-linear-gradient(right, #FFF 95%, #ddd 100%);
        background-image:-moz-linear-gradient(right, #FFF 95%, #ddd 100%);
        background-image:-o-linear-gradient(right, #FFF 95%, #ddd 100%);
        background-image:-ms-linear-gradient(right, #FFF 95%, #ddd 100%);
    }

    .container {
        margin: auto;
    }

    @media screen and (max-width: 768px) {
        .container {
            width: 100%; /* Szélesség 100%-ra állítva, ha a kijelző szélessége 768px alatt van */
        }
    }    

    .button {
        background-color: #1eb304;
        border-radius: 10px;
        color: white;
        height: 25px;
    }
</style>
</head>
<body>
    <div class="container">
        <div id="book">
            <div class="cover">
                <div class="top-center">

                </div>
                <h1>Amigos történetek</h1>
                <div class="bottom-left">

                </div>
                <div class="bottom-right">

                </div>
            </div>
        </div>

        <div id="controls">
            <button id="prev-page" class="button">Előző</button>
            <button id="next-page" class="button">Következő</button>
            <label for="page-number">Oldalszám:</label> <input type="text" size="3" id="page-number"> of <span id="number-pages"></span>
        </div>
    </div>
    

    <script type="text/javascript">

        // Sample using dynamic pages with turn.js
        var stories = @json($stories);

        function sortWords(stories, cim) {
            let storiesWords = stories.split(' ')
            while(storiesWords.indexOf("") != -1) {
                storiesWords.splice(storiesWords.indexOf(""), 1)
            }

            let storiesForPages = []
            var pageObject = {
                page: "",
                title: cim
            };
            storiesForPages.push(pageObject)
            let oldal = "";
            let k = 0;
            let numberOfWordsPerPage = 85
            for (let i = 0; i < storiesWords.length; i++) {
                if (k !== numberOfWordsPerPage) {
                    oldal += storiesWords[i] + " "
                    k++
                }
                else {
                    k = 0
                    var pageObject = {
                        page: oldal,
                        title: cim
                    };
                    storiesForPages.push(pageObject)
                    oldal = storiesWords[i] + " "
                }
            }

            return storiesForPages
        }

        let bigStory = []
        for (let i = 0; i < stories.length; i++) {
            let seged = sortWords(stories[i].story, stories[i].title)
            bigStory = bigStory.concat(seged)
        }

        var numberOfPages = bigStory.length; 

        // Adds the pages that the book will need
        function addPage(page, book) {        
            // First check if the page is already in the book
            if (!book.turn('hasPage', page)) {
                // Create an element for this page
                var element = $('<div />', {'class': 'page '+((page%2==0) ? 'odd' : 'even'), 'id': 'page-'+page}).html('<i class="loader"></i>');
                // If not then add the page
                book.turn('addPage', element, page);
                // Let's assume that the data is coming from the server and the request takes 1s.
                setTimeout(function(){
                        if (bigStory[page-2].page === "") {
                            element.html('<div class="data-title"><h1>'+ bigStory[page-2].title + "</h1><div class='page-number-"+((page%2==0) ? 'odd' : 'even')+"'>" + page + '. oldal</div></div>');
                        }
                        else {
                            element.html('<div class="data"><div class="top-small-title">' + bigStory[page-2].title + '<span class="top-number">' + page + '. oldal</span></div><br><p class="szoveg">' + bigStory[page-2].page + '</p></div>' + '<div class="page-number-' + ((page % 2 == 0) ? 'odd' : 'even') + '">' + page + '. oldal</div>');

                        }
                }, 1000);
            }
        }

        $(window).ready(function(){
            var $book = $('#book');
            $book.turn({
                acceleration: true,
                pages: numberOfPages,
                elevation: 50,
                gradients: !$.isTouch,
                when: {
                    turning: function(e, page, view) {
                        // Gets the range of pages that the book needs right now
                        var range = $(this).turn('range', page);
                        // Check if each page is within the book
                        for (page = range[0]; page<=range[1]; page++)
                            addPage(page, $(this));
                    },
                    turned: function(e, page) {
                        $('#page-number').val(page);
                    }
                }
            });

            $('#number-pages').html(numberOfPages);

            $('#page-number').keydown(function(e){
                if (e.keyCode==13)
                    $book.turn('page', $('#page-number').val());
            });

            $('#prev-page').click(function(){
                $book.turn('previous');
            });

            $('#next-page').click(function(){
                $book.turn('next');
            });
        });

        $(window).bind('keydown', function(e){
            if (e.target && e.target.tagName.toLowerCase()!='input')
                if (e.keyCode==37)
                    $('#book').turn('previous');
                else if (e.keyCode==39)
                    $('#book').turn('next');
        });

    </script>
</body>
</html>
