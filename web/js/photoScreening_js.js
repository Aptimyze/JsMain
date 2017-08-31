function createPicTuble(picType, picSrc) {
    
    var mainDivAttr = {class:"gallery"};
    var anchorAttr = {target:"_blank", href:picSrc};
    var imgAttr = {src:picSrc};
    
    var descDivAttr = {class:"desc", text:picType};
    
    var mainDiv = $("<div />", mainDivAttr);
    var anchorEle = $("<a />", anchorAttr);
    anchorEle.append($("<img />", imgAttr));
    
    mainDiv.append(anchorEle);
    mainDiv.append($("<div />", descDivAttr));
    
    return mainDiv;
}


function updateDOM(data) {
    var totalAvaWidth = window.innerWidth - 150;
    
    var mainDiv = $("<div />", {id:"content", class:"marLeft15Per"});
    var formEle = $("<form />", {id:"", method:"post", onsubmit: "formSubmit(this); return false;"});
    
    var approveInputAttr = {type:"radio", name:data.Id, value:"approve", checked:"checked"};
    var editInputAttr = {type:"radio", name:data.Id, value:"edit"};
   
    $.each(data.imgs,function(picType, picSrc) {
        formEle.append(createPicTuble(picType, picSrc));
    });
    formEle.append($("<div />",{"style" : "cliear:both"}));
    
    var inputDiv = $("<div />",{class : 'marLeft35Per'});
    
    inputDiv.append($("<input>",approveInputAttr));
    inputDiv.append($("<label />", {text : "Approve"}))
    inputDiv.append($("<input>",editInputAttr))
    inputDiv.append($("<label />", {text : "Edit"}))
    
    formEle.append(inputDiv);
    
    var inputDiv = $("<div />",{class : 'marLeft35Per'});
    inputDiv.append($("<input />",{type:"submit", width:"100px"}));
    
    formEle.append(inputDiv);
    
    mainDiv.append(formEle);
    $("#container").append(mainDiv);
}   

function getApiData()
{
    url = "/operations.php/photoScreening/benchmark?json_response=1";
    $.ajax({
           type: "GET",
           url: url,
           success: updateDOM
         });
}

function formSubmit(target)
{
    url = "/photoScreenSubmit.php";
    $.ajax({
           type: "POST",
           url: url,
           data: $(target).serialize(), // serializes the form's elements.
           success: function(data)
           {
               console.log(data); // show response from the php script.
               //TODO : Remove the old data
           }
    });
}

