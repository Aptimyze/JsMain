export const preProcessInput = (data) => {
  let outPutData = [];
  outPutData.length = 0;
  for (let i in data) {
    let _c = data[i];
    for (let j in _c) {
      let obj = {
        name: "",
        code: ""
      };
      obj.name = _c[j];
      obj.code = j;
      outPutData.push(obj);
      break
    }
  }
  return outPutData
};
export const processFromMultipleArrays = (data, param, field) => {
  let outPutData = [];
  outPutData.length = 0;
  let inputData = data[param];
  if (field === "income" && !inputData) {
    inputData = data[128]
  }
  if (field === "religion") {
    inputData = data
  }
  for (let i in inputData) {
    let _c = inputData[i][0];
    for (let j in _c) {
      let obj = {
        name: "",
        code: ""
      };
      obj.name = _c[j];
      obj.code = j;
      outPutData.push(obj);
      break
    }

  }
  return outPutData
};

export const indianCities = data => {
  let outPutData = [];
  outPutData.length = 0;
  for (let i in data[0]) {
    let _c = data[0][i];
    for (let j in _c) {
      let obj = {
        name: "",
        code: ""
      };
      obj.name = _c[j];
      obj.code = j;
      outPutData.push(obj);
      break
    }

  }
  return outPutData
};

export const determineCourses = (data, tableData) => {
  let outPut = '';
  let newTableData = [];
  for (let i in tableData) {
    for (let j in tableData[i]) {
      let obj = {
        name: "",
        value: ""
      };
      obj.value = tableData[i][j] ? tableData[i][j].split(',') : tableData[i][j];
      obj.name = j;
      newTableData.push(obj);
      break
    }

  }
  for (let i in newTableData) {
    if (newTableData[i].value.indexOf(data) !== -1) {
      outPut = newTableData[i].name;
      break
    }
  }
  return outPut;
};
export const simpleProcessData = (data) => {
  let outPutData = [];
  outPutData.length = 0;
  for (let j in data) {
    let obj = {
      name: "",
      code: ""
    };
    obj.name = data[j];
    obj.code = (data[j] === "None") ? "0" : j;
    outPutData.push(obj);
  }
  return outPutData
};

export const removeDuplicate = (arr) => {
  let results = [];
  let idsSeen = {}, idSeenValue = {};
  for (let i = 0, len = arr.length, id; i < len; ++i) {
    id = arr[i].name;
    if (idsSeen[id] !== idSeenValue) {
      if (arr[i].code == -1) {
        arr.splice(i, 1)
      }
      results.push(arr[i]);
      idsSeen[id] = idSeenValue;
    }

  }
  return results;
}

// calculate age from now
export const calculateAge = (birthDate) => {
  birthDate = new Date(birthDate);
  let otherDate = new Date();
  let years = (otherDate.getFullYear() - birthDate.getFullYear());

  if (otherDate.getMonth() < birthDate.getMonth() ||
    otherDate.getMonth() == birthDate.getMonth() && otherDate.getDate() < birthDate.getDate()) {
    years--;
  }

  return years;
}

export const contructLoginData = (userInput, regPageArray, src) => {
  let regPage = JSON.parse(JSON.stringify(regPageArray));
  if (src === "incomplete") {
      for (let key in userInput) {
        if (userInput.hasOwnProperty(key)
            && key.includes('dtofbirth')) {
          if (key === 'dtofbirth_day') {
            regPage['editFieldArr[DTOFBIRTH][day]'] = userInput[key];
          }
          if (key === 'dtofbirth_month') {
            regPage['editFieldArr[DTOFBIRTH][month]'] = userInput[key];
          }
          if (key === 'dtofbirth_year') {
            regPage['editFieldArr[DTOFBIRTH][year]'] = userInput[key];
          }
        }
        else {
          let szKey = "editFieldArr[" + key.toUpperCase().trim() + "]";
          regPage[szKey] = (typeof userInput[key] === "undefined") ? "" : userInput[key];
        }
      }
    }
  else {
    for (let key in userInput) {
      if (key.includes('dtofbirth') && userInput[key] && userInput[key] != "undefined") {
        if (key === 'dtofbirth_day') {
          let szKey = "reg[dtofbirth][day]";
          regPage[szKey] = userInput[key];
        }
        if (key === 'dtofbirth_month') {
          let szKey = "reg[dtofbirth][month]";
          regPage[szKey] = userInput[key];
        }
        if (key === 'dtofbirth_year') {
          let szKey = "reg[dtofbirth][year]";
          regPage[szKey] = userInput[key];
        }

      } else if (key === 'phone_mob' && userInput[key] && userInput[key] != "undefined") {
        let arrPhone = userInput[key].split(',');
        let szKey = "reg[phone_mob][isd]";
        regPage[szKey] = (typeof arrPhone[0] === "undefined") ? "" : arrPhone[0];
        szKey = "reg[phone_mob][mobile]";
        regPage[szKey] = (typeof arrPhone[1] === "undefined") ? "" : arrPhone[1];
      } else if (key === 'relationship' && userInput[key]
        && userInput[key] != "undefined") {
        if (userInput[key].indexOf('4') != -1)/*For Friend and relative we have to pass one userInput[key]*/
          userInput[key] = '4';
        let szKey = "reg[" + key.trim() + "]";
        regPage[szKey] = (typeof userInput[key] === "undefined") ? "" : userInput[key];
      } else if (key === 'password' && userInput[key]
        && userInput[key] != "undefined") {
        let szKey = "reg[" + key.trim() + "]";
        regPage[szKey] = (typeof userInput[key] === "undefined") ? "" : encodeURIComponent(userInput[key]);
      } else {
        let szKey = "reg[" + key.trim() + "]";
        regPage[szKey] = (typeof userInput[key] === "undefined") ? "" : userInput[key];
      }
    }
  }

  return regPage;
};

export const getQueryString = (field, url) => {
  var href = url ? url : window.location.href;
  var reg = new RegExp('[?&]' + field + '=([^&#]*)', 'i');
  var string = reg.exec(href);
  return string ? string[1] : 'home';
};


export const myTrim = (inputString)=>{
  if (typeof inputString != "string") { return inputString; }
  let retValue = inputString;
  let ch = retValue.substring(0, 1);
  while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
    retValue = retValue.substring(1, retValue.length);
    ch = retValue.substring(0, 1);
  }
  ch = retValue.substring(retValue.length-1, retValue.length);
  while (ch == " " || ch == '\n' || ch == '\t' || ch == '\r') {
    retValue = retValue.substring(0, retValue.length-1);
    ch = retValue.substring(retValue.length-1, retValue.length);
  }
  while (retValue.indexOf("  ") != -1) {
    retValue = retValue.substring(0, retValue.indexOf("  ")) + retValue.substring(retValue.indexOf("  ")+1, retValue.length);
  }
  return retValue;
}

export const trimNewLine = (string)=>{
  return string.replace(/^\s*|\s*$/g, "");
}

export const getSearchParameters = () => {
  let prmstr = window.location.search.substr(1);
  return prmstr != null && prmstr != "" ? transformToAssocArray(prmstr) : {};
}

function transformToAssocArray(prmstr) {
  let params = {};
  let prmarr = prmstr.split("&");
  for (let i = 0; i < prmarr.length; i++) {
    let tmparr = prmarr[i].split("=");
    params[tmparr[0]] = decodeURIComponent(tmparr[1]);
  }
  return params;
}

