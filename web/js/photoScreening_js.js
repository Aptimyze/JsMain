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
    
    var approveInputAttr = {type:"radio", name:"edit", value:"false", checked:"checked"};
    var editInputAttr = {type:"radio", name:"edit", value:"true"};

   
    $.each(data.imgs,function(picType, picSrc) {
        formEle.append(createPicTuble(picType, picSrc));
    });
    formEle.append($("<div />",{"style" : "clear:both"}));
    
    var inputDiv = $("<div />",{class : 'marLeft35Per'}) ;
    
    inputDiv.append($("<input>",approveInputAttr));
    inputDiv.append($("<label />", {text : "Approve"}))
    inputDiv.append($("<input>",editInputAttr))
    inputDiv.append($("<label />", {text : "Edit"}))
    formEle.append("<input  type=hidden name=\"cid\" value="+cid+">");
    formEle.append("<input  type=hidden name=\"name\" value="+name+">");
    formEle.append("<input  type=hidden name=\"pid\" value="+data.Id+">");
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
    url = "/operations.php/photoScreening/benchmark?json_response=1";
    $.ajax({
           type: "POST",
           url: url,
           data: $(target).serialize(), // serializes the form's elements.
           success: function(data)
           {
               console.log(data); // show response from the php script.
               $("#content").remove();
               updateDOM(data);

           }
    });
}

