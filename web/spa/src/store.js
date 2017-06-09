import thunk from "redux-thunk";
import { createStore, applyMiddleware, combineReducers } from "redux";
import LoginReducer from "./login/reducers/LoginReducer";
import ProfileReducer from "./viewProfile/reducers/ProfileReducer";

const store = createStore(combineReducers({LoginReducer,ProfileReducer}),{},applyMiddleware(thunk));

export default store;
