export const setItem = (key, value) => {
  localStorage.setItem(key, JSON.stringify(value));
};

export const getItem = (key) => {
  let result = null;
  if (localStorage.getItem(key) !== "undefined" && localStorage.getItem(key)) {
    result = JSON.parse(localStorage.getItem(key));
  }
  return result

};

export const removeItem = (key) => {
  localStorage.removeItem(key);
};