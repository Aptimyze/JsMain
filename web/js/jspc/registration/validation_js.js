//error messages list
var arrErors = {
	"RELIGION_ERROR_1":"Please choose married only if you are muslim male.",
	"RELIGION_REQUIRED":"Please choose a religion",
	"DOB_REQUIRED":"Please provide date of birth",
	"DOB_ERROR_1":"Please provide a valid date of birth",
	"DOB_ERROR_2":"Profile should be at least 18 years old to register",
	"DOB_ERROR_3":"Profile should be at least 21 years old to register",
	"PASSWORD_REQUIRED":"Please provide a password",
	"PASSWORD_INVALID":"Password should be at least 8 characters long",
	"PASSWORD_COMMON":"Password you have chosen is not secure",
	"PINCODE_ERROR_1":"Please provide a pin code that belongs to <City>",
	"PINCODE_REQUIRED":"Please provide a pin code of your residence",
	"EMAIL_REQUIRED":"Please provide an email",
	"EMAIL_INVALID":"Please provide your email in proper format",
	"EMAIL_INVALID_DOMAIN":"Provide your email in proper format, e.g. raj1984@gmail.com",
	"EMAIL_EXIST":"An account with this Email already exists",
	"MOBILE_REQUIRED":"Please provide a mobile number",
	"MOBILE_INVALID":"Please provide  a valid mobile number",
	"ISD_REQUIRED":"Please provide an ISD code",
	"ISD_INVALID":"Please provide a valid ISD code",
	"NAME_REQUIRED":"Please enter name of person whose profile is being created",
	"GENDER_REQUIRED":"Please provide a gender",
	"CPF_REQUIRED":"Please choose whose profile is being created.",
	"MSTATUS_REQUIRED":"Please provide a marital status",
	"HAVECHILDREN_REQUIRED":"Please mention whether you have children",
	"HEIGHT_REQUIRED":"Please provide a Height",
	"CITY_REQUIRED":"Please mention the City you are living in",
  "CITYREG_REQUIRED":"Please mention the City you are living in",
        "CASTE_REQUIRED":"Please provide a Caste",
        "SECT_REQUIRED":"Please provide a Sect",
	"COUNTRY_REQUIRED":"Please mention the Country you are living in",
  "COUNTRYREG_REQUIRED":"Please mention the Country you are living in",
  "STATEREG_REQUIRED":"Please mention the State you are living in",
	"MTONGUE_REQUIRED":"Please provide a Mother Tongue",
	"ABOUTME_ERROR":"To appear in search results, please write about yourself in atleast 100 letters",
	"HDEGREE_REQUIRED":"Please provide a degree",
	"OCCUPATION_REQUIRED":"Please provide an occupation",
	"INCOME_REQUIRED":"Please provide an income range",
	"ABOUTME_REQUIRED":"Please write about yourself (Don't mention your name)",
	"NAME_ERROR":"Name should have alphabets only"
};
//regular expressions for validations
var name_regex = /^[a-zA-Z\s\.\']*$/;
var email_regex = /^([A-Za-z0-9._%+-]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i;
var isd_regex = /^([+]{0,1}[0-9]{1,3})$/;
var phonePatternIndia = /^([7-9]{1}[0-9]{9})$/;
var phonePatternOther = /^([1-9]{1}[0-9]{5,13})$/;
var isdCodes = ["0", "91","+91"];
//pincode array and validation starting substring for particular states
var pincodeArr={'DE00':{0:["1100","2013","1220","2010","1210","1245"],1:4,2:"Please provide a pincode that belongs to Delhi"},"MH04":{0:["400","401","410","421","416"],1:3,2:"Please provide a pincode that belongs to Mumbai"},"MH08":{0:["410","411","412","413"],1:3,2:"Please provide a pincode that belongs to Pune"}};
//email invalid domain list
var invalidDomainArr = new Array("jeevansathi", "dontreg","mailinator","mailinator2","sogetthis","mailin8r","spamherelots","thisisnotmyrealemail","jsxyz","jndhnd");
//invalid password not alowed list
var invalidPasswords = new Array("jeevansathi","matrimony","password","marriage","12345678","123456789","1234567890");

//base class for validator
(function() 
{
	var validator = (function () 
	{
//validate function used to validate
		validator.prototype.validate = function(value) 
		{
			this.fieldValue = value;
//required check. Its flag is set and retrieved from html for each element whether is compulsory or not
			if(this.required!=false&&this.required!="false")
			{
//check if the value of that field is not blank
				if(!this.fieldValue||this.fieldValue.length==0||!this.fieldValue.trim()||this.fieldValue=="undefined")
				{
					var fieldType= this.fieldType.toUpperCase();
//show errors if blank                  
					this.error=arrErors[fieldType+"_REQUIRED"];
					return false;
				}
			}
		}
		//constructor
		function validator(inputFieldElement) 
		{
			this.inputFieldElement= inputFieldElement;
			this.fieldType=this.inputFieldElement[0]['fieldType'];
			this.fieldValue='';
			this.required=this.inputFieldElement[0]['required'];
			this.fieldId = this.inputFieldElement[0]['fieldId'];
			this.error='';
		}
		validator.prototype.getValue =function(fieldName)
                {
			val =  eval('document.getElementById("'+fieldName+'_value").value');
			return val;
                }
		return validator;
	})();
	this.validator=validator;
}).call(this);
// inherted class from validator for email
(function() {
    var emailValidator = (function () {

      //inheriting form base class
      inheritsFrom(emailValidator,validator);

      //constructor
      function emailValidator(fieldElement) {
      emailValidator.prototype.parent.constructor.call(this,fieldElement);
      }
//validate fucntion for email
      emailValidator.prototype.validate = function() 
      {
	var email = this.getValue("email").trim();
	inputData[this.inputFieldElement[0].formKey]=email;
	emailValidator.prototype.parent.validate.call(this,email);
	if(this.error)
		return false;
	var emailError;
//match email regular expression
	var emailPattern =this.emailPattern(email);
//set error on regex failure
	if(!emailPattern)
		emailError =arrErors['EMAIL_INVALID'];
	if(!emailError)
	{
//check for invalid domains
		var emailDomain =this.invalidDomain(email);
//show error if invalid domain
		if(!emailDomain)
			emailError =arrErors['EMAIL_INVALID_DOMAIN'];
	}
	if(emailError)
	{
//set error for the element
		this.error= emailError;
		return false;
	}
	return true;
      }
//invalid domain check function
      emailValidator.prototype.invalidDomain =function(email)
	{
                        var value =email;
                        var start = value.indexOf('@');//starting point index of domain marked by @
                        var end = value.lastIndexOf('.');//ending point index of domain marked by .
                        var diff = end-start-1;
                        var user = value.substr(0,start);
                        var len = user.length;
                        var domain = value.substr(start+1,diff).toLowerCase();//domain string:: substring from start to end point index

                        if(invalidDomainArr.indexOf(domain.toLowerCase()) != -1)
                                return false;
                        else if(domain == 'gmail')
                        {
                                if(!(len >= 6 && len <=30))
                                        return false;
                        }
                        else if(domain == 'yahoo' || domain == 'ymail' || domain == 'rocketmail' )
                        {
                                if(!(len >= 4 && len <=32))
                                        return false;
                        }
                        else if(domain == 'rediff')
                        {
                                if(!(len >= 4 && len <=30))
                                        return false;
                        }
                        else if(domain == 'sify')
                        {
                                if(!(len >= 3 && len <=16))
                                        return false;
                        }
                        return true;
                }
//email regex matching function
      emailValidator.prototype.emailPattern =function(email)
                {
                        if(!email_regex.test(email))
                                return false;
                        else
                                return true;
                }
   return emailValidator;
   })();
   this.emailValidator=emailValidator;
 }).call(this);

// inherted class from validator for dob
(function() {
    var dobValidator = (function () {
      //inheriting form base class
      inheritsFrom(dobValidator,validator);
      //constructor
      function dobValidator(fieldElement) {
      dobValidator.prototype.parent.constructor.call(this,fieldElement);
      }
//validate functionf or dob
      dobValidator.prototype.validate = function() 
      {
	this.dob_date=this.getValue("date");
	this.dob_month=this.getValue('month');
	this.dob_year=this.getValue('year');
	this.gender = this.getValue('gender');
// if date of birth is missing, set error
	if(!this.dob_date||!this.dob_month||!this.dob_year)
	{
		this.error =arrErors['DOB_REQUIRED'];
		return false;
	}
	var bInValidDate = false;
//make date with input fields
	var correspondingDate = new Date(this.dob_year,parseInt(this.dob_month)-1,this.dob_date);
	var szErrorKey = '';
	if(correspondingDate.getDate() !== parseInt(this.dob_date))
		bInValidDate = true;

	if(correspondingDate.getMonth()+1 !== parseInt(this.dob_month))
		bInValidDate = true;

	if(correspondingDate.getFullYear() !== parseInt(this.dob_year))
		bInValidDate = true;
	/*Year check for male and female*/
	if(!bInValidDate)
	{
		var currDate            = new Date();
		var maxMaleYear         = currDate.getFullYear() - 21;
		var maxFeMaleYear       = currDate.getFullYear() - 18;
		var maxMonth            = currDate.getMonth() + 1;
		var maxDate             = currDate.getDate();
		var maxYear             = maxFeMaleYear;
		if(this.gender == 'M')
		{
			maxYear                 = maxMaleYear;
		}

		if(correspondingDate.getFullYear() === maxYear)
		{
			var selectedMonth = correspondingDate.getMonth() + 1;
			if(selectedMonth > maxMonth || (selectedMonth === maxMonth && correspondingDate.getDate() > maxDate))
			{
				bInValidDate = true;
				this.error = arrErors['DOB_ERROR_2'];/*Female Should be 18  Year old*/
				if(this.gender == 'M')
					this.error = arrErors['DOB_ERROR_3'];/*Male Should be 21  Year old*/
                                return false;
			}
		}
	}
	else
	{
		szErrorKey = 'DOB_ERROR_1';
	}
	if(bInValidDate)
	{
		this.error = arrErors[szErrorKey];
		return false;
	}
	return (!bInValidDate);
      }
   return dobValidator;
   })();
   this.dobValidator=dobValidator;
 }).call(this);

// inherted class from validator for password
(function() {
    var passwordValidator = (function () {
      inheritsFrom(passwordValidator,validator);
      function passwordValidator(fieldElement) 
      {
	      passwordValidator.prototype.parent.constructor.call(this,fieldElement);
      }
//validate function for password
      passwordValidator.prototype.validate = function() 
      {
	password = this.getValue('password');
	email = this.getValue('email');
	passwordValidator.prototype.parent.validate.call(this,password);
	if(this.error)
		return false;
	var passError ='';
//required error
	if(password==''){
		passError =arrErors['PASSWORD_REQUIRED'];
	}
//length check error for password
	if(password && password.length<8)
		passError =arrErors['PASSWORD_INVALID'];
	if(!passError){
		var passCommon =this.checkCommonPassword(password);//check if the password is common like jeevansathi etc
		var userPassMatch =this.checkPasswordUserName(password,email);// check if the password is same as username
//if common or guessable password then set error
		if(!passCommon || !userPassMatch)
			passError =arrErors['PASSWORD_COMMON'];
	}
	if(passError){
		this.error=passError;
		return false;
	}
	return true;
      }
//function to check if entered password is common from a list of passwords
	passwordValidator.prototype.checkCommonPassword=function(password)
	{
		if(invalidPasswords.indexOf(password.toLowerCase()) != -1)
			return false;
		return true;
	}
//function to check if the password is same as the emailids user name
	passwordValidator.prototype.checkPasswordUserName =function(pass, email)
	{
		if(typeof email === "undefined")
			return true;
		var end = email.indexOf('@');
		var username = email.substr(0,end);
		if((String(pass) != String(username) && String(pass) != String(email)))
			return true;
		return false;
	}
	return passwordValidator;
   })();
   this.passwordValidator=passwordValidator;

 }).call(this);

// inherted class from validator for pincode
(function() {
    var pincodeValidator = (function () {
      inheritsFrom(pincodeValidator,validator);
      function pincodeValidator(fieldElement) {
      pincodeValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      pincodeValidator.prototype.validate = function() 
      {
	pincode = this.getValue("pin");
	city = this.getValue("cityReg");
	pincodeValidator.prototype.parent.validate.call(this,pincode);
	if(this.error)
		return false;
	if(!city)
		return false;
	if(pincodeArr.hasOwnProperty)
		var initial = pincode.toString().substring(0,pincodeArr[city][1]);
	if(pincode.toString().length <6)
	{
		this.error = arrErors['PINCODE_ERROR_1'].replace(/<City>/g,$("#city-inputBox_set").val());
		return false;
	}
	if(pincodeArr[city][0].indexOf(initial) === -1)
	{ 
		this.error = pincodeArr[city][2];
		return false;
	}
	return true;
}
return pincodeValidator;
   })();
   this.pincodeValidator=pincodeValidator;
 }).call(this);

// inherted class from validator for phone
(function() {
    var phoneValidator = (function () {
      //inheriting form base class
      inheritsFrom(phoneValidator,validator);
      //constructor
      function phoneValidator(fieldElement) {
      phoneValidator.prototype.parent.constructor.call(this,fieldElement);
      }

      phoneValidator.prototype.validate = function() 
      {
	var isdVal = this.getValue("isd");
	var phone = this.getValue("mobile");
	phoneValidator.prototype.parent.validate.call(this,phone);
        isdVal = isdVal.trim().replace(/^[0]+/g,"");
        phone = phone.trim().replace(/^[0]+/g,"");
	if(isdVal!='' && phone!='')
	{
//removing pre zeros appended
		inputData[this.inputFieldElement[0].formKey].isd=isdVal;
		inputData[this.inputFieldElement[0].formKey].mobile=phone;
	}
        if(isdVal=="")
		this.error=arrErors['ISD_REQUIRED'];
        else if(!isd_regex.test(isdVal))
		this.error =arrErors['ISD_INVALID'];
        else if(phone=="")
		this.error =arrErors['MOBILE_REQUIRED'];
        else if(!this.checkValidPhone(isdVal,phone))
		this.error =arrErors['MOBILE_INVALID'];
        if(this.error)
		return false;
	return true;
      }
//function to check if the phone number is valid basis length
      phoneValidator.prototype.checkValidPhone =function(mobileISD,mobileNumber)
	{
		if(isdCodes.indexOf(mobileISD)!= -1 && (mobileNumber.length!=10 || !phonePatternIndia.test(mobileNumber)))
			return false;
		else if(mobileNumber.length<6 || mobileNumber.length>14 || !phonePatternOther.test(mobileNumber))
			return false;
		return true;
	}
   return phoneValidator;
   })();
   this.phoneValidator=phoneValidator;
 }).call(this);
// inherted class from validator for religion
(function() {
    var religionValidator = (function () {
      //inheriting form base class
      inheritsFrom(religionValidator,validator);
      //constructor
      function religionValidator(fieldElement) {
      religionValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      religionValidator.prototype.validate = function()
      {
	var religion = this.getValue("religion");
	religionValidator.prototype.parent.validate.call(this,religion);
	var gender = this.getValue("gender");
	var mstatus=this.getValue("mstatus");
//check that mstatus is married then either gender should be male and religion should be muslim
	if(mstatus=="M" &&(gender=="F"||religion!="2"))
	{
		this.error = arrErors['RELIGION_ERROR_1'];
		return false;
	}
	return true;
      }
   return religionValidator;
   })();
   this.religionValidator=religionValidator;
 }).call(this);
// inherted class from validator for create profile for cpf
(function() {
    var cpfValidator = (function () {
      //inheriting form base class
      inheritsFrom(cpfValidator,validator);
      //constructor
      function cpfValidator(fieldElement) {
      cpfValidator.prototype.parent.constructor.call(this,fieldElement);
      }

      cpfValidator.prototype.validate = function()
      {
        var cpf = this.getValue("cpf");
	cpfValidator.prototype.parent.validate.call(this,cpf);
	if(this.error)
                return false;
        return true;
      }
   return cpfValidator;
   })();
   this.cpfValidator=cpfValidator;
 }).call(this);
// inherted class from validator for gender
(function() {
    var genderValidator = (function () {
      //inheriting form base class
      inheritsFrom(genderValidator,validator);
      //constructor
      function genderValidator(fieldElement) {
      genderValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      genderValidator.prototype.validate = function()
      {
        var gender = this.getValue("gender");
        genderValidator.prototype.parent.validate.call(this,gender);
        if(this.error)
                return false;
        return true;
      }
   return genderValidator;
   })();
   this.genderValidator=genderValidator;
 }).call(this);

// inherted class from validator for mstatus
(function() {
    var mstatusValidator = (function () {
      //inheriting form base class
      inheritsFrom(mstatusValidator,validator);
      //constructor
      function mstatusValidator(fieldElement) {
      mstatusValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      mstatusValidator.prototype.validate = function()
      {
        var mstatus = this.getValue("mstatus");
        mstatusValidator.prototype.parent.validate.call(this,mstatus);
        if(this.error)
                return false;
        return true;
      }
   return mstatusValidator;
   })();
   this.mstatusValidator=mstatusValidator;
 }).call(this);
// inherted class from validator for have children
(function() {
    var haveChildrenValidator = (function () {
      //inheriting form base class
      inheritsFrom(haveChildrenValidator,validator);
      //constructor
      function haveChildrenValidator(fieldElement) {
      haveChildrenValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      haveChildrenValidator.prototype.validate = function()
      {
        var haveChildren = this.getValue("haveChildren");
        haveChildrenValidator.prototype.parent.validate.call(this,haveChildren);
        if(this.error)
                return false;
        return true;
      }
   return haveChildrenValidator;
   })();
   this.haveChildrenValidator=haveChildrenValidator;
 }).call(this);
 // inherted class from validator for manglik
(function() {
    var manglikValidator = (function () {
      //inheriting form base class
      inheritsFrom(manglikValidator,validator);
      //constructor
      function manglikValidator(fieldElement) {
      manglikValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      manglikValidator.prototype.validate = function()
      {
        var manglik = this.getValue("manglik");
        manglikValidator.prototype.parent.validate.call(this,manglik);
        if(this.error)
                return false;
        return true;
      }
   return manglikValidator;
   })();
   this.manglikValidator=manglikValidator;
 }).call(this);
 // inherted class from validator for sub caste
(function() {
    var subcasteValidator = (function () {
      //inheriting form base class
      inheritsFrom(subcasteValidator,validator);
      //constructor
      function subcasteValidator(fieldElement) {
      subcasteValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      subcasteValidator.prototype.validate = function()
      {
        var subcaste = this.getValue("subcaste");
        subcasteValidator.prototype.parent.validate.call(this,subcaste);
        if(this.error)
                return false;
        return true;
      }
   return subcasteValidator;
   })();
   this.subcasteValidator=subcasteValidator;
 }).call(this);
 
 // inherted class from validator for pg college
(function() {
    var pgCollegeValidator = (function () {
      //inheriting form base class
      inheritsFrom(pgCollegeValidator,validator);
      //constructor
      function pgCollegeValidator(fieldElement) {
      pgCollegeValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      pgCollegeValidator.prototype.validate = function()
      {
        var pgCollege = this.getValue("pgCollege");
        pgCollegeValidator.prototype.parent.validate.call(this,pgCollege);
        if(this.error)
                return false;
        return true;
      }
   return pgCollegeValidator;
   })();
   this.pgCollegeValidator=pgCollegeValidator;
 }).call(this);
 
 // inherted class from validator for ug college
(function() {
    var ugCollegeValidator = (function () {
      //inheriting form base class
      inheritsFrom(ugCollegeValidator,validator);
      //constructor
      function ugCollegeValidator(fieldElement) {
      ugCollegeValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      ugCollegeValidator.prototype.validate = function()
      {
        var ugCollege = this.getValue("ugCollege");
        ugCollegeValidator.prototype.parent.validate.call(this,ugCollege);
        if(this.error)
                return false;
        return true;
      }
   return ugCollegeValidator;
   })();
   this.ugCollegeValidator=ugCollegeValidator;
 }).call(this);
 
 (function() {
    var otherUgDegreeValidator = (function () {
      //inheriting form base class
      inheritsFrom(otherUgDegreeValidator,validator);
      //constructor
      function otherUgDegreeValidator(fieldElement) {
      otherUgDegreeValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      otherUgDegreeValidator.prototype.validate = function()
      {
          var otherUg = this.getValue("otherUgDegree");
        otherUgDegreeValidator.prototype.parent.validate.call(this,otherUg);
        if(this.error)
                return false;
        return true;
      }
   return otherUgDegreeValidator;
   })();
   this.otherUgDegreeValidator=otherUgDegreeValidator;
 }).call(this);
 
  (function() {
    var otherPgDegreeValidator = (function () {
      //inheriting form base class
      inheritsFrom(otherPgDegreeValidator,validator);
      //constructor
      function otherPgDegreeValidator(fieldElement) {
      otherPgDegreeValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      otherPgDegreeValidator.prototype.validate = function()
      {
        var otherPg = this.getValue("otherPgDegree");
        otherPgDegreeValidator.prototype.parent.validate.call(this,otherPg);
        if(this.error)
                return false;
        return true;
      }
   return otherPgDegreeValidator;
   })();
   this.otherPgDegreeValidator=otherPgDegreeValidator;
 }).call(this);

// inherted class from validator for height
(function() {
    var heightValidator = (function () {
      //inheriting form base class
      inheritsFrom(heightValidator,validator);
      //constructor
      function heightValidator(fieldElement) {
      heightValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      heightValidator.prototype.validate = function()
      {
        var height = this.getValue("height");
        heightValidator.prototype.parent.validate.call(this,height);
        if(this.error)
                return false;
        return true;
      }
   return heightValidator;
   })();
   this.heightValidator=heightValidator;
 }).call(this);
 // inherted class from validator for country
(function() {
    var countryRegValidator = (function () {
      //inheriting form base class
      inheritsFrom(countryRegValidator,validator);
      //constructor
      function countryRegValidator(fieldElement) {
      countryRegValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      countryRegValidator.prototype.validate = function()
      {
        var country = this.getValue("countryReg");
        countryRegValidator.prototype.parent.validate.call(this,country);
        if(this.error)
        {
            this.error=arrErors["COUNTRYREG_REQUIRED"];
            return false;
        }
        return true;
      }
   return countryRegValidator;
   })();
   this.countryRegValidator=countryRegValidator;
 }).call(this);
  // inherted class from validator for state
(function() {
    var stateRegValidator = (function () {
      //inheriting form base class
      inheritsFrom(stateRegValidator,validator);
      //constructor
      function stateRegValidator(fieldElement) {
      stateRegValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      stateRegValidator.prototype.validate = function()
      {
        var state = this.getValue("stateReg");
        stateRegValidator.prototype.parent.validate.call(this,state);
        if(this.error)
        {
            this.error=arrErors["STATEREG_REQUIRED"];
            return false;
        }
        return true;
      }
   return stateRegValidator;
   })();
   this.stateRegValidator=stateRegValidator;
 }).call(this);
 
  // inherted class from validator for country
(function() {
    var cityRegValidator = (function () {
      //inheriting form base class
      inheritsFrom(cityRegValidator,validator);
      //constructor
      function cityRegValidator(fieldElement) {
      cityRegValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      cityRegValidator.prototype.validate = function()
      {
        var city = this.getValue("cityReg");
        cityRegValidator.prototype.parent.validate.call(this,city);
        if(this.error)
        {
            this.error=arrErors["CITYREG_REQUIRED"];
            return false;
        }
        return true;
      }
   return cityRegValidator;
   })();
   this.cityRegValidator=cityRegValidator;
 }).call(this);
 
// inherted class from validator for city
(function() {
    var cityValidator = (function () {
      //inheriting form base class
      inheritsFrom(cityValidator,validator);
      //constructor
      function cityValidator(fieldElement) {
      cityValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      cityValidator.prototype.validate = function()
      {
        var city = this.getValue("city");
        cityValidator.prototype.parent.validate.call(this,city);
        if(this.error)
	{
		if(this.inputFieldElement[0].fromIndia!=0)
			this.error=arrErors["COUNTRY_REQUIRED"];
                return false;
	}
        return true;
      }
   return cityValidator;
   })();
   this.cityValidator=cityValidator;
 }).call(this);
// inherted class from validator for mtongue
(function() {
    var mtongueValidator = (function () {
      //inheriting form base class
      inheritsFrom(mtongueValidator,validator);
      //constructor
      function mtongueValidator(fieldElement) {
      mtongueValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      mtongueValidator.prototype.validate = function()
      {
        var mtongue = this.getValue("mtongue");
        mtongueValidator.prototype.parent.validate.call(this,mtongue);
        if(this.error)
                return false;
        return true;
      }
   return mtongueValidator;
   })();
   this.mtongueValidator=mtongueValidator;
 }).call(this);
// inherted class from validator for caste
(function() {
    var casteValidator = (function () {
      //inheriting form base class
      inheritsFrom(casteValidator,validator);
      //constructor
      function casteValidator(fieldElement) {
      casteValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      casteValidator.prototype.validate = function()
      {
        var caste = this.getValue("caste");
        casteValidator.prototype.parent.validate.call(this,caste);
        if(this.error){
            if((inputData["religion"] =="2")||(inputData["religion"] =="3"))
	      this.error=arrErors["SECT_REQUIRED"];
                return false;
        }
        return true;
      }
   return casteValidator;
   })();
   this.casteValidator=casteValidator;
 }).call(this);

// inherted class from validator for hdegree
(function() {
    var hdegreeValidator = (function () {
      //inheriting form base class
      inheritsFrom(hdegreeValidator,validator);
      //constructor
      function hdegreeValidator(fieldElement) {
      hdegreeValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      hdegreeValidator.prototype.validate = function()
      {
        var hdegree = this.getValue("hdegree");
        hdegreeValidator.prototype.parent.validate.call(this,hdegree);
        if(this.error)
                return false;
        return true;
      }
   return hdegreeValidator;
   })();
   this.hdegreeValidator=hdegreeValidator;
 }).call(this);
 
 (function() {
    var ugDegreeValidator = (function () {
      //inheriting form base class
      inheritsFrom(ugDegreeValidator,validator);
      //constructor
      function ugDegreeValidator(fieldElement) {
      ugDegreeValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      ugDegreeValidator.prototype.validate = function()
      {
        var ugDegree = this.getValue("ugDegree");
        ugDegreeValidator.prototype.parent.validate.call(this,ugDegree);
        if(this.error)
                return false;
        return true;
      }
   return ugDegreeValidator;
   })();
   this.ugDegreeValidator=ugDegreeValidator;
 }).call(this);
 
 (function() {
    var pgDegreeValidator = (function () {
      //inheriting form base class
      inheritsFrom(pgDegreeValidator,validator);
      //constructor
      function pgDegreeValidator(fieldElement) {
      pgDegreeValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      pgDegreeValidator.prototype.validate = function()
      {
        var pgDegree = this.getValue("pgDegree");
        pgDegreeValidator.prototype.parent.validate.call(this,pgDegree);
        if(this.error)
                return false;
        return true;
      }
   return pgDegreeValidator;
   })();
   this.pgDegreeValidator=pgDegreeValidator;
 }).call(this);

// inherted class from validator for occupation
(function() {
    var occupationValidator = (function () {
      //inheriting form base class
      inheritsFrom(occupationValidator,validator);
      //constructor
      function occupationValidator(fieldElement) {
      occupationValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      occupationValidator.prototype.validate = function()
      {
        var occupation = this.getValue("occupation");
        occupationValidator.prototype.parent.validate.call(this,occupation);
        if(this.error)
                return false;
        return true;
      }
   return occupationValidator;
   })();
   this.occupationValidator=occupationValidator;
 }).call(this);

// inherted class from validator for income
(function() {
    var incomeValidator = (function () {
      //inheriting form base class
      inheritsFrom(incomeValidator,validator);
      //constructor
      function incomeValidator(fieldElement) {
      incomeValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      incomeValidator.prototype.validate = function()
      {
        var income = this.getValue("income");
        incomeValidator.prototype.parent.validate.call(this,income);
        if(this.error)
                return false;
        return true;
      }
   return incomeValidator;
   })();
   this.incomeValidator=incomeValidator;
 }).call(this);

// inherted class from validator for aboutme
(function() {
    var aboutmeValidator = (function () {
      //inheriting form base class
      inheritsFrom(aboutmeValidator,validator);
      //constructor
      function aboutmeValidator(fieldElement) {
      aboutmeValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      aboutmeValidator.prototype.validate = function()
      {
        var aboutme = this.getValue("aboutme");
	inputData[this.inputFieldElement[0].formKey]=aboutme;
        aboutmeValidator.prototype.parent.validate.call(this,aboutme);
	if(aboutme==aboutmePlaceholder)
		this.error=arrErors["ABOUTME_REQUIRED"];
        if(this.error)
                return false;
	aboutme = aboutme.replace(/\s\s+/g, ' ').replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	if(aboutme.length<100)
		this.error=arrErors["ABOUTME_ERROR"];
	if(aboutme.length>3000)
		this.error=arrErors["ABOUTME_ERROR"];
        return true;
      }
   return aboutmeValidator;
   })();
   this.aboutmeValidator=aboutmeValidator;
 }).call(this);
 // inherted class from validator for aboutfamily
(function() {
     var aboutfamilyValidator = (function () {
      //inheriting form base class
      inheritsFrom(aboutfamilyValidator,validator);
      //constructor
      function aboutfamilyValidator(fieldElement) {
      aboutfamilyValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      aboutfamilyValidator.prototype.validate = function()
      {
        var aboutfamily = this.getValue("aboutfamily");
	inputData[this.inputFieldElement[0].formKey]=aboutfamily;
        aboutfamilyValidator.prototype.parent.validate.call(this,aboutfamily);
        return true;
      }
   return aboutfamilyValidator;
   })();
   this.aboutfamilyValidator=aboutfamilyValidator;
 }).call(this);
// inherted class from validator for name
(function() {
    var nameValidator = (function () {
      //inheriting form base class
      inheritsFrom(nameValidator,validator);
      //constructor
      function nameValidator(fieldElement) {
      nameValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      nameValidator.prototype.validate = function()
      {
        var name = this.getValue("name").replace(/\s{2,}/g, ' ').replace(/^\s\s*/, '').replace(/\s\s*$/, '');
	inputData[this.inputFieldElement[0].formKey]=name;
        name_of_user = name.replace(/\./gi, " ");
        name_of_user = name_of_user.replace(/dr|ms|mr|miss/gi, "");
        name_of_user = name_of_user.replace(/\,|\'/gi, "");
        name_of_user = $.trim(name_of_user.replace(/\s+/gi, " "));
        var allowed_chars = /^[a-zA-Z\s]+([a-zA-Z\s]+)*$/i;
        if($.trim(name_of_user)== "" || !allowed_chars.test($.trim(name_of_user)))
	{
                this.error = "Please provide a valid Full Name";
		return false;
        }
	else
	{
                var nameArr = name_of_user.split(" ");
                if(nameArr.length<2){
                        this.error = "Please provide your first name along with surname, not just the first name";
			return false;
                }
        }
        return true;
      }
   return nameValidator;
   })();
   this.nameValidator=nameValidator;
 }).call(this);
(function() {
    var stateValidator = (function () {
      //inheriting form base class
      inheritsFrom(stateValidator,validator);
      //constructor
      function stateValidator(fieldElement) {
      stateValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      stateValidator.prototype.validate = function()
      {
        var state = this.getValue("state");
        stateValidator.prototype.parent.validate.call(this,state);
        if(this.error)
        {
                if(this.inputFieldElement[0].fromIndia!=0)
                        this.error=arrErors["COUNTRY_REQUIRED"];
                return false;
        }
        return true;
      }
   return stateValidator;
   })();
   this.stateValidator=stateValidator;
 }).call(this);
(function() {
    var familyCityValidator = (function () {
      //inheriting form base class
      inheritsFrom(familyCityValidator,validator);
      //constructor
      function familyCityValidator(fieldElement) {
      familyCityValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      familyCityValidator.prototype.validate = function()
      {
        var familyCity = this.getValue("familyCity");
        familyCityValidator.prototype.parent.validate.call(this,familyCity);
        if(this.error)
        {
                if(this.inputFieldElement[0].fromIndia!=0)
                        this.error=arrErors["COUNTRY_REQUIRED"];
                return false;
        }
        return true;
      }
   return familyCityValidator;
   })();
   this.familyCityValidator=familyCityValidator;
 }).call(this);
 // inherted class from validator for manglik
(function() {
    var horoscopeMatchValidator = (function () {
      //inheriting form base class
      inheritsFrom(horoscopeMatchValidator,validator);
      //constructor
      function horoscopeMatchValidator(fieldElement) {
      horoscopeMatchValidator.prototype.parent.constructor.call(this,fieldElement);
      }
      horoscopeMatchValidator.prototype.validate = function()
      {
        var horoscopeMatch = this.getValue("horoscopeMatch");
        horoscopeMatchValidator.prototype.parent.validate.call(this,horoscopeMatch);
        if(this.error)
                return false;
        return true;
      }
   return horoscopeMatchValidator;
   })();
   this.horoscopeMatchValidator=horoscopeMatchValidator;
 }).call(this);
