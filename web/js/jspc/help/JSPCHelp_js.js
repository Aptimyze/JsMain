var catogaries = [],
    quesAnsList = [],
    keyWordArray = [],
    finalKeyWord = [],
    counter, pt, quesWords = [],
    lastChar, str,
    jsonData,
    ignoreWord = ["", "a", "about", "above", "across", "after", "again", "against", "all", "almost", "alone", "along", "already", "also", "although", "always", "among", "an", "and", "another", "any", "anybody", "anyone", "anything", "anywhere", "are", "area", "areas", "around", "as", "ask", "asked", "asking", "asks", "at", "away", "b", "back", "backed", "backing", "backs", "be", "became", "because", "become", "becomes", "been", "before", "began", "behind", "being", "beings", "best", "better", "between", "big", "both", "but", "by", "c", "came", "can", "cannot", "case", "cases", "certain", "certainly", "clear", "clearly", "come", "could", "d", "did", "differ", "different", "differently", "do", "does", "done", "down", "downed", "downing", "downs", "during", "e", "each", "early", "either", "end", "ended", "ending", "ends", "enough", "even", "evenly", "ever", "every", "everybody", "everyone", "everything", "everywhere", "f", "face", "faces", "fact", "facts", "far", "felt", "few", "find", "finds", "first", "for", "four", "from", "full", "fully", "further", "furthered", "furthering", "furthers", "g", "gave", "general", "generally", "get", "gets", "give", "given", "gives", "go", "going", "good", "goods", "got", "great", "greater", "greatest", "group", "grouped", "grouping", "groups", "h", "had", "has", "have", "having", "he", "her", "here", "herself", "hi", "high", "higher", "highest", "him", "himself", "his", "how", "however", "i", "if", "important", "in", "interested", "interesting", "into", "is", "it", "its", "itself", "j", "just", "k", "keep", "keeps", "kind", "knew", "know", "known", "knows", "l", "large", "largely", "last", "later", "latest", "least", "less", "let", "lets", "like", "likely", "long", "longer", "longest", "m", "made", "make", "making", "man", "many", "may", "me", "men", "might", "more", "most", "mostly", "mr", "mrs", "much", "must", "my", "myself", "n", "necessary", "need", "needed", "needing", "needs", "never", "new", "newer", "newest", "next", "no", "nobody", "non", "noone", "not", "nothing", "now", "nowhere", "number", "numbers", "o", "of", "off", "often", "old", "older", "oldest", "on", "once", "one", "only", "open", "opened", "opening", "opens", "or", "other", "others", "our", "out", "over", "p", "part", "parted", "parting", "parts", "per", "perhaps", "place", "places", "point", "pointed", "pointing", "points", "possible", "present", "presented", "presenting", "presents", "problem", "problems", "put", "puts", "q", "quite", "r", "rather", "really", "right", "room", "rooms", "s", "said", "same", "saw", "say", "says", "second", "seconds", "see", "seem", "seemed", "seeming", "seems", "sees", "several", "shall", "she", "should", "show", "showed", "showing", "shows", "side", "sides", "since", "small", "smaller", "smallest", "so", "some", "somebody", "someone", "something", "somewhere", "state", "states", "still", "such", "sure", "t", "take", "taken", "than", "that", "the", "their", "them", "then", "there", "therefore", "these", "they", "thing", "things", "think", "thinks", "this", "those", "though", "thought", "thoughts", "three", "through", "thus", "to", "today", "together", "too", "took", "toward", "turn", "turned", "turning", "turns", "two", "u", "under", "until", "up", "upon", "us", "use", "used", "uses", "v", "very", "w", "want", "wanted", "wanting", "wants", "was", "way", "ways", "we", "well", "wells", "went", "were", "what", "when", "where", "whether", "which", "while", "who", "whole", "whose", "why", "will", "with", "within", "without", "work", "worked", "working", "works", "would", "x", "y", "year", "years", "yet", "you", "young", "younger", "youngest", "your", "yours", "z"];
$(document).ready(function (e) {
    //fetching data from API
    $("#searchQuesList").removeClass('disp-none').hide();
    getData();
    var validInfo = true;
    $("#phoneInp, #quesInp").find("input").keydown(function (e) {
        $(this).parent().removeClass("errorDiv");
    });
    //post query button click
    $(".postQueryBtn").on("click", function () {
        var parent = $(this).parent().parent().attr('parent');
        $(this).removeClass("color5 cursp"), $("#" + parent + " .formTable").show();
    });
    //back button click defined on section container
    $("#backBtn").on("click", function () {
        $("#mainDiv").show(), $("#sectionDiv").hide();
    });
    //click bind on Search funtion
    $("#searchBtn").on("click", function () {
        var e = jQuery.Event("keydown");
        e.keyCode = 13;
        $('#searchInput').trigger(e);
        setTimeout(function () {
            if ($(".quesTable2 tr").length == 0) {
                $("#noResultDiv").removeClass("disp-none"), $("#searchQuesList, #sectionList").hide(), $("#searchBack").removeClass("vishid");
            }
        }, 200);
    });
    //back button click defined on search container
    $("#searchBack").on("click", function () {
        $("#sectionList").show(), $("#searchQuesList").hide(), $("#searchBack").addClass("vishid"), $("#searchInput").val(""), $("#noResultDiv").addClass("disp-none"), $("#queryForm tr").each(function () {
            $(this).removeClass("errorTr")
        });
    });
    //on selecting dropdown option
    $(".catDropDown").each(function(){
        $(this).on("click", function () {
            $(this).find("i").toggleClass("chosen-container-active");
            $(".dropOption").toggleClass("disp-none");
        }); 
    })
    $("body").on("click",function(e){
        var arrAllowedClass = ["selectedDrop","dropOptionLi"];
        if($(e.target).attr("class")) {
            if(arrAllowedClass.indexOf($(e.target).attr("class")) == -1 && $(e.target).attr("class").indexOf("catDropDown") == -1) {
                $(this).find("i").removeClass("chosen-container-active");
                $(".dropOption").addClass("disp-none");
            }
        }
    });

    $(".goBackBtn").on("click", function () {
        location.reload();
    });
    //posting form
    $(".postBtnClk").on("click", function () {
        var parent = $(this).parent().parent().parent().parent().parent().attr('parent');
        //check for email and question
        var validData = true,
            email = $("#" + parent + " #queryForm .email").val(),
            emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if (!emailReg.test(email) || email == "") {
            $("#" + parent + " #queryForm tr:first-child").addClass("errorTr"), validData = false;
        }
        if ($("#" + parent + " #queryForm .askQuestion").val() == "") {
            // console.log("#" + parent + " #queryForm .askQuestion");
            $("#" + parent + " #queryForm tr:nth-child(4)").addClass("errorTr"), validData = false;
        }
        if (validData == true) {
            // console.log("Fire Query");
            $("#" + parent + " #postSubmitDiv").removeClass("disp-none"), $("#" + parent + " #formDiv").hide();
            var username = $("#" + parent + " #username").val(),
                query = $("#" + parent + " #queryForm .askQuestion").val(),
                category = $("#" + parent + " #queryForm .selectedDrop").text();
            if (category == "Please Select an option") {
                category = "";
            }
            // console.log(username + category);
            $.ajax({
                type: "POST",
                url: '/api/v1/help/helpQuery',
                cache: false,
                timeout: 5000,
                data: {
                    email: email,
                    username: username,
                    query: query,
                    category: category
                },
                success: function (result) {
                    // console.log("S");
                },
                error: function (result) {
                    // console.log("S");
                }
            });
        }
    });
    $(".email, .askQuestion").keypress(function (e) {
        $(this).parent().parent().removeClass("errorTr");
    });
    applySearchAlgo();
});

function getData() {
    $.ajax({
        type: "GET",
        url: "/api/v1/help/publicQuestions",
        success: function (a) {
            jsonData = a.Response;
            catogaries = Object.keys(jsonData);
            setDropDownData();
            //appending catogary on main page
            appendSectionList();
            //binding click on View All of each catogary
            bindSectionClick();
        },
        //error on not recieving data of ques/ans
        error: function (a) {
            $("#catTitle").html("Something Went Wrong");
        }
    })
}
//set drop down values in catogaries in form 
function setDropDownData() {
    setTimeout(function () {
        for (var j = 0; j < catogaries.length; j++) {
            $(".dropOption").append("<li class='dropOptionLi' id=" + catogaries[j].split(' ')[0] + ">" + catogaries[j] + "</li>");
        }
        $(".dropOption li").on("click", function () {
            $(".selectedDrop").html($(this).html());
        });
    }, 500);
}
//search algorithm - detect key pressed
function applySearchAlgo() {
    var idArray = [];
    $("#searchInput").keydown(function (e) {
        lastChar = $(this).val().substr($(this).val().length - 1);
        //check for back button and other keys
        if (e.keyCode == 8 && $(this).val().length == 1) {
            $("#sectionList").show(), $("#searchQuesList").hide(), $("#noResultDiv").addClass("disp-none"), $("#searchBack").addClass("vishid");
        }
        //check for enter, comma, space, question, full stop and semicolon key pressed
        else if (e.keyCode == 32 || e.keyCode == 13 || e.keyCode == 188 || e.keyCode == 186 || e.keyCode == 190 || e.keyCode == 191 || e.keyCode == 8 && lastChar == " " || lastChar == "?" || lastChar == "," || lastChar == ";" || lastChar == ".") {
            idArray = [];
            str = $(this).val();
            keyWordArray = str.replace("?", " ").replace(";", " ").replace(",", " ").replace(".", " ").split(" ");
            finalKeyWord = [];
            $(".quesTable2").html("");
            //getting final list after ignoring keywords
            finalKeyWord = getFinalList(keyWordArray);
            //searching final list
            var quesStr = "",
                quesKeyWords = [];
            $.each(finalKeyWord, function (index, value) {
                $.each(quesAnsList, function (index2, value2) {
                    //getting keywords of each ques
                    quesStr = value2.QUESTION;
                    quesKeyWords = quesStr.replace("?", " ").replace(";", " ").replace(",", " ").replace(".", " ").split(" ");
                    //searching each keyword of question with search input
                    $.each(quesKeyWords, function (index3, value3) {
                        if (value3.toLowerCase().indexOf(value.toLowerCase()) != -1) {
                            var idPresent = false;
                            $.each(idArray, function (index4, value4) {
                                if (value4 == value2.ID) {
                                    idPresent = true;
                                }
                            })
                            if (idPresent == false) {
                                $("#searchQuesList").show(), $("#sectionList").hide(), $("#searchBack").removeClass("vishid");
                                $(".quesTable2").append("<tr id='ques_" + value2.ID + "'><td>Q</td><td>" + value2.QUESTION + "</td></tr><tr><td>A </td><td>" + value2.ANSWER + "</td></tr>");
                                idArray.push(value2.ID);
                            }
                        }
                    });
                });
            });
            //showing no result found on enter key pressed if there is no question selected
            if (e.keyCode == 13 && $(".quesTable2 tr").length == 0) {
                $("#noResultDiv").removeClass("disp-none"), $("#searchQuesList, #sectionList").hide(), $("#searchBack").removeClass("vishid");
            } else if ($(".quesTable2 tr").length != 0) {
                $("#noResultDiv").addClass("disp-none");
            }
            //binding each ques click to view its answer
            bindQuesClick("quesTable2");
        }
    });
}

function bindQuesClick(elem) {
    $("." + elem + " tr:nth-child(odd)").on("click", function () {
        $(this).next().toggle();
        $(this).find("td:nth-child(2)").toggleClass("mb");
    });
}

function getFinalList(elem) {
    var pt = 0,
        finalList = [];
    $.each(elem, function (index, value) {
        counter = false;
        $.each(ignoreWord, function (index2, value2) {
            if (value.toLowerCase() === value2.toLowerCase() || value == "") {
                counter = true;
            }
        });
        if (counter == false) {
            finalList[pt] = value;
            pt++;
        }
    });
    return finalList;
}

function appendSectionList() {
    var valueEven, catQuesList;
    $.each(catogaries, function (index, value) {
        if (index % 2 == 0) {
            valueEven = value;
        } else {
            $("#catogaryTable").append("<tr><td><div>" + valueEven + "</div><div class='viewBtn' id='view_" + valueEven.split(" ")[0] + "'>View all questions</div></td><td><div>" + value + "</div><div class='viewBtn' id='view_" + value.split(" ")[0] + "'>View all questions</div></td></tr>");
            valueEven = "";
        }
        catQuesList = jsonData[value];
        $.each(Object.keys(jsonData[value]), function (index2, value2) {
            quesAnsList.push(catQuesList[value2]);
        });
    });
    if (valueEven !== "") {
        $("#catogaryTable").append("<tr><td><div>" + valueEven + "</div><div class='viewBtn' id='view_" + valueEven.split(" ")[0] + "'>View all questions</div></td></tr>");
    }
}

function bindSectionClick() {
    var selectedCat = "",
        that, quesAns, noOfQues, quesIdArr = [];
    $(".viewBtn").each(function (index, element) {
        $(this).on("click", function () {
            that = $(this);
            window.scrollTo(0, 0);
            $.each(catogaries, function (index, value) {
                if ((that).prev().text() == value) {
                    selectedCat = value;
                }
            });
            //Adding catogary heading on catogary page
            $("#sectionHeading").html(selectedCat + "&nbsp;(All questions)");
            quesAns = jsonData[selectedCat], noOfQues = Object.keys(jsonData[selectedCat]).length, quesIdArr = Object.keys(jsonData[selectedCat]);
            $(".quesTable").html("");
            //adding ques ans on catogary page
            for (var i = 0; i < noOfQues; i++) {
                $(".quesTable").append("<tr id='ques_" + quesIdArr[i] + "'><td>Q</td><td>" + quesAns[quesIdArr[i]].QUESTION + "</td></tr><tr><td>A </td><td>" + quesAns[quesIdArr[i]].ANSWER + "</td></tr>");
            }
            //binding each ques click to view its answer
            bindQuesClick("quesTable");
            //showing catogary page and hiding main page
            $("#mainDiv").hide(), $("#sectionDiv").show();
        });
    });
}

function submitHelpForm() {
    // console.log("Test");
}