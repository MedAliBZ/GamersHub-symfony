$(document).ready(function () {
    const settings = {
        "async": true,
        "crossDomain": true,
        "url": `https://google-news1.p.rapidapi.com/search?q=${$("#gameName").html()}&country=US&lang=en&limit=10&when=30d`,
        "method": "GET",
        "headers": {
            "x-rapidapi-host": "google-news1.p.rapidapi.com",
            "x-rapidapi-key": "api-key"
        }
    };
    // 3712cf1d6cmshb54d0c7fbc4a044p107200jsn8b9476230474 , 65d14ca226msh3d214dd3211828ap12c985jsnca370da08070 , c121cb4a87msh74915bfd8985fd3p1a7558jsn0b9c3509786b
    $.ajax(settings).done(function (response) {
        html = "";
        response.articles.map((e) => html += `<div class="articles-game">
                                <h4 style="text-align: center;margin-bottom: 20px;">${e.title}</h4>
                                <p style="margin-bottom: 20px;">${e.published_date.split('T')[0]}</p>
                                <div style="display: flex;justify-content: space-between;align-items: center;width: 100%;">
                                    <div style="display: flex;">
                                        <h6 style="margin-right: 10px">Source: </h6> <h6 id="article-source">${e.source.title}</h6>
                                    </div>
                                    <div style="display: flex;">
                                        <a target="_blank" href=${e.link}>Visit Article <i class="fas fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>`)
        $("#all-articles").append(html);
    }).fail((res) => {
        $("#all-articles").append("<p>No articles to display.</p>");
    });

    $('#like').click((e) => {
        let likes = $('#numberLikes').html().split(' ')[0] * 1;
        if ($('#like').attr('class') === "fas fa-heart") {
            $.post("http://127.0.0.1:8000/api/game/unlike",
                {
                    username: $('#username').html(),
                    gameName: $("#gameName").html()
                },
                () => {
                    $('#like').removeClass('fas fa-heart').addClass('far fa-heart');
                    if(likes === 2){
                        $('#numberLikes').html('1 like');
                    }else
                        $('#numberLikes').html(`${likes-1} likes`);
                });
        } else {
            $.post("http://127.0.0.1:8000/api/game/like",
                {
                    username: $('#username').html(),
                    gameName: $("#gameName").html()
                },
                () => {
                    $('#like').removeClass('far fa-heart').addClass('fas fa-heart');
                    if(likes === 0){
                        $('#numberLikes').html('1 like');
                    }else
                        $('#numberLikes').html(`${likes+1} likes`);
                });
        }

    })
})