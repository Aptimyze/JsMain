import thunk from "redux-thunk";
import { createStore, applyMiddleware, combineReducers } from "redux";
import LoginReducer from "./login/reducers/LoginReducer";
import ProfileReducer from "./viewProfile/reducers/ProfileReducer";
import MyjsReducer from "./myjs/reducers/MyjsReducer";
import AlbumReducer from "./photoAlbum/reducers/AlbumReducer"
import verifiedVisitReducer from "./verifiedVisit/reducers/verifiedVisitReducer"
import ForgotPasswordReducer from "./forgotPassword/reducers/ForgotPasswordReducer"
import Jsb9Reducer from "./common/reducers/Jsb9Reducer"



const store = createStore(combineReducers({LoginReducer,ProfileReducer,MyjsReducer,AlbumReducer,verifiedVisitReducer,jsb9Reducer,ForgotPasswordReducer}),{},applyMiddleware(thunk));


export default store;
