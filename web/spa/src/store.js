import thunk from "redux-thunk";
import { createStore, applyMiddleware } from "redux";
import LoginReducer from "./login/reducers/LoginReducer";

const store = createStore(LoginReducer,{},applyMiddleware(thunk));

export default store;
