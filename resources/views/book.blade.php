<!doctype html>
<html>
<head>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/filament/turn.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-beta1/css/bootstrap.min.css" rel="stylesheet">


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
        background:#333;
    }

    #book .cover h1{
        color:white;
        text-align:center;
        font-size:50px;
        line-height:500px;
        margin:0px;
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
        border: 2px solid black;
        width: 50%;
    }
</style>
</head>
<body>
    <div class="container">
        <div id="book">
            <div class="cover"><h1>Amigos történetek</h1></div>
        </div>

        <div id="controls">
            <button id="prev-page">Previous</button>
            <button id="next-page">Next</button>
            <label for="page-number">Page:</label> <input type="text" size="3" id="page-number"> of <span id="number-pages"></span>
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
            let numberOfWordsPerPage = 75
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
                            element.html('<div class="data">'+ bigStory[page-2].title + "<br>csa<br>" + page + '</div>');    
                        }
                        else {
                            element.html('<div class="data">'+ bigStory[page-2].title + "<br>" + bigStory[page-2].page + page + '</div>');
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

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-beta1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
