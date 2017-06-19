var catogaries = [],
    quesAnsList = [],
    jsonData = {},
    ignoreWord = ["", "a", "about", "above", "across", "after", "again", "against", "all", "almost", "alone", "along", "already", "also", "although", "always", "among", "an", "and", "another", "any", "anybody", "anyone", "anything", "anywhere", "are", "area", "areas", "around", "as", "ask", "asked", "asking", "asks", "at", "away", "b", "back", "backed", "backing", "backs", "be", "became", "because", "become", "becomes", "been", "before", "began", "behind", "being", "beings", "best", "better", "between", "big", "both", "but", "by", "c", "came", "can", "cannot", "case", "cases", "certain", "certainly", "clear", "clearly", "come", "could", "d", "did", "differ", "different", "differently", "do", "does", "done", "down", "downed", "downing", "downs", "during", "e", "each", "early", "either", "end", "ended", "ending", "ends", "enough", "even", "evenly", "ever", "every", "everybody", "everyone", "everything", "everywhere", "f", "face", "faces", "fact", "facts", "far", "felt", "few", "find", "finds", "first", "for", "four", "from", "full", "fully", "further", "furthered", "furthering", "furthers", "g", "gave", "general", "generally", "get", "gets", "give", "given", "gives", "go", "going", "good", "goods", "got", "great", "greater", "greatest", "group", "grouped", "grouping", "groups", "h", "had", "has", "have", "having", "he", "her", "here", "herself", "hi", "high", "higher", "highest", "him", "himself", "his", "how", "however", "i", "if", "important", "in", "interested", "interesting", "into", "is", "it", "its", "itself", "j", "just", "k", "keep", "keeps", "kind", "knew", "know", "known", "knows", "l", "large", "largely", "last", "later", "latest", "least", "less", "let", "lets", "like", "likely", "long", "longer", "longest", "m", "made", "make", "making", "man", "many", "may", "me", "men", "might", "more", "most", "mostly", "mr", "mrs", "much", "must", "my", "myself", "n", "necessary", "need", "needed", "needing", "needs", "never", "new", "newer", "newest", "next", "no", "nobody", "non", "noone", "not", "nothing", "now", "nowhere", "number", "numbers", "o", "of", "off", "often", "old", "older", "oldest", "on", "once", "one", "only", "open", "opened", "opening", "opens", "or", "other", "others", "our", "out", "over", "p", "part", "parted", "parting", "parts", "per", "perhaps", "place", "places", "point", "pointed", "pointing", "points", "possible", "present", "presented", "presenting", "presents", "problem", "problems", "put", "puts", "q", "quite", "r", "rather", "really", "right", "room", "rooms", "s", "said", "same", "saw", "say", "says", "second", "seconds", "see", "seem", "seemed", "seeming", "seems", "sees", "several", "shall", "she", "should", "show", "showed", "showing", "shows", "side", "sides", "since", "small", "smaller", "smallest", "so", "some", "somebody", "someone", "something", "somewhere", "state", "states", "still", "such", "sure", "t", "take", "taken", "than", "that", "the", "their", "them", "then", "there", "therefore", "these", "they", "thing", "things", "think", "thinks", "this", "those", "though", "thought", "thoughts", "three", "through", "thus", "to", "today", "together", "too", "took", "toward", "turn", "turned", "turning", "turns", "two", "u", "under", "until", "up", "upon", "us", "use", "used", "uses", "v", "very", "w", "want", "wanted", "wanting", "wants", "was", "way", "ways", "we", "well", "wells", "went", "were", "what", "when", "where", "whether", "which", "while", "who", "whole", "whose", "why", "will", "with", "within", "without", "work", "worked", "working", "works", "would", "x", "y", "year", "years", "yet", "you", "young", "younger", "youngest", "your", "yours", "z"];
$(document).ready(function (e) {
    getData();
    applySearchAlgo();
    //preventDefaultBackBtnClick();
});

function getData() {
    $.ajax({
        type: "GET",
        url: "/api/v1/help/publicQuestions",
        success: function (a) {
            jsonData = a.Response;
            catogaries = Object.keys(jsonData);
            //append list of sections
            appendSectionList();
            //bind section click
            bindSectionClick();
        }
    });
}
var selectedId = "";

function bindQuesClick(elem, quesAns2) {
    $("#" + elem + " li").each(function (index, element) {
        $(this).off("click").on("click", function () {
            selectedId = $(this).attr("id").split("_")[1];
            $.each(quesAns2, function (index, value) {
                if (value.ID == selectedId) {
                    $(".questionHeading").html(value.QUESTION);
                    $(".answer").html(value.ANSWER);
                }
            });
            $("#sectionQuesDiv, #firstPageDiv,#hamburgerIcon, #backBtnSection, #pageBack").addClass("dispnone"), $("#questionAnswerDiv,#backBtnQues").removeClass("dispnone");
            $("#backBtnQues").off("click").on("click", function () {
                if (elem == "quesList") {
                    $("#sectionQuesDiv,#backBtnSection").removeClass("dispnone"), $("#firstPageDiv, #questionAnswerDiv,#backBtnQues,#hamburgerIcon,#pageBack").addClass("dispnone");
                } else {
                    $("#firstPageDiv,#backBtnSection").removeClass("dispnone"), $("#sectionListing,#hamburgerIcon,#sectionQuesDiv, #questionAnswerDiv,#backBtnQues,#pageBack").addClass("dispnone");
                }
            });
        });
    });
}
var idArray = [];
//search algorithm - detect key pressed
function applySearchAlgo() {
    $("#searchPId").on("keyup", function (e) {
        lastChar = $(this).val().substr($(this).val().length - 1);
        var currentChar = $(this).val().substr($(this).val().length);
        if ($(this).val().length == 1 && e.keyCode != 8) {
            $("#questionListing,#backBtnSection").removeClass("dispnone"), $("#sectionListing,#hamburgerIcon,#pageBack").addClass("dispnone");
            bindBackButton("search");
        }
        if ($(this).val().length == 0) {
            $("#sectionListing,#hamburgerIcon,#pageBack").removeClass("dispnone"), $("#questionListing,#backBtnSection,#noResultDiv").addClass("dispnone");
        } else if (lastChar == " " || lastChar == "?" || lastChar == "," || lastChar == ";" || lastChar == "." || e.keyCode == 8 || e.keyCode == 13) {
            str = $(this).val();
            keyWordArray = str.replace("?", " ").replace(";", " ").replace(",", " ").replace(".", " ").split(" ");
            finalKeyWord = [];
            $("#quesList2").html("");
            //getting final list after ignoring keywords
            finalKeyWord = getFinalList(keyWordArray);
            //searching final list
            searchFinalList(finalKeyWord);
            //showing no result found on enter key pressed if there is no question selected
            if (e.keyCode == 13 && $("#quesList2 li").length == 0) {
                $("#backBtnSection,#noResultDiv").removeClass("dispnone"), $("#hamburgerIcon,#sectionListing,#pageBack").addClass("dispnone");
                bindBackButton("search");
            } else {
                $("#noResultDiv").addClass("dispnone");
            }
            //binding each ques click to view its answer
            bindQuesClick("quesList2", quesAnsList);
        }
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
            finalList[pt] = value, pt++;
        }
    });
    return finalList;
}

function bindBackButton(elem) {
    $("#backBtnSection").off("click").on("click", function () {
        if (elem == "section") {
            $("#firstPageDiv").removeClass("dispnone"), $("#sectionQuesDiv, #questionAnswerDiv").addClass("dispnone");
        } else if (elem == "search") {
            $("#sectionListing").removeClass("dispnone"), $("#questionListing,#noResultDiv").addClass("dispnone"), $("#searchPId").val("");
        }
        $("#hamburgerIcon, #pageBack").removeClass("dispnone"), $("#backBtnQues, #backBtnSection").addClass("dispnone");
    });
}

function appendSectionList() {
    $.each(catogaries, function (index, value) {
        $(".sectionList").append("<li id='section_" + value.split(" ")[0] + "'>" + value + "</li>");
        var catQuesList = jsonData[value];
        $.each(Object.keys(jsonData[value]), function (index2, value2) {
            quesAnsList.push(catQuesList[value2]);
        });
    });
}

function bindSectionClick() {
    var selectedCat = "",
        that, quesAns, noOfQues, quesIdArr = [];
    $(".sectionList li").each(function (index, element) {
        $(this).on("click", function () {
            that = $(this);
            $.each(catogaries, function (index, value) {
                if ($(that).text() == value) {
                    selectedCat = value;
                    $(".sectionHeading").html(value);
                    quesAns = jsonData[selectedCat], noOfQues = Object.keys(jsonData[selectedCat]).length, quesIdArr = Object.keys(jsonData[selectedCat]);
                    $("#quesList").html("");
                    for (var i = 0; i < noOfQues; i++) {
                        $("#quesList").append("<li id='ques_" + quesIdArr[i] + "'>" + quesAns[quesIdArr[i]].QUESTION + "</li>");
                        bindQuesClick("quesList", quesAns);
                    }
                }
            });
            $("#firstPageDiv, #questionAnswerDiv,#hamburgerIcon, #backBtnQues,#pageBack").addClass("dispnone"), $("#sectionQuesDiv,#backBtnSection").removeClass("dispnone");
            bindBackButton("section");
        });
    });
}

function searchFinalList(elem) {
    var quesStr = "",
        quesKeyWords = [];
    idArray = [];
    $.each(elem, function (index, value) {
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
                        $("#questionListing,#backBtnSection").removeClass("dispnone"), $("#sectionListing,#hamburgerIcon").addClass("dispnone");
                        bindBackButton("search");
                        $("#quesList2").append("<li id='ques_" + value2.ID + "'>" + value2.QUESTION + "</li>");
                        idArray.push(value2.ID);
                    }
                }
            });
        });
    });
}

function preventDefaultBackBtnClick() {
    if (typeof history.pushState === "function") {
        history.pushState("jibberish", null, null);
        window.onpopstate = function () {
            history.pushState('newjibberish', null, null);
            $("#overlayHead").find("i").each(function (index, element) {
                if (!$(this).hasClass("dispnone")) {
                    $(this).click();
                }
            });
        };
    } else {
        var ignoreHashChange = true;
        window.onhashchange = function () {
            if (!ignoreHashChange) {
                ignoreHashChange = true;
                window.location.hash = Math.random();
            } else {
                ignoreHashChange = false;
            }
            $("#overlayHead").find("i").each(function (index, element) {
                if (!$(this).hasClass("dispnone")) {
                    $(this).click();
                }
            });
        };
    }
}