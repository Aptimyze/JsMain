import thunk from "redux-thunk";
import { createStore, applyMiddleware, combineReducers } from "redux";
import LoginReducer from "./login/reducers/LoginReducer";
import ProfileReducer from "./viewProfile/reducers/ProfileReducer";
import MyjsReducer from "./myjs/reducers/MyjsReducer";

const store = createStore(combineReducers({LoginReducer,ProfileReducer,MyjsReducer}),{},applyMiddleware(thunk));

export default store;
