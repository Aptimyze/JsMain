import thunk from "redux-thunk";
import { createStore, applyMiddleware, combineReducers } from "redux";
import LoginReducer from "./login/reducers/LoginReducer";
import ProfileReducer from "./viewProfile/reducers/ProfileReducer";
import MyjsReducer from "./myjs/reducers/MyjsReducer";
import PhotoReducer from "./common/reducers/PhotoReducer";
import AlbumReducer from "./photoAlbum/reducers/AlbumReducer"
import verifiedVisitReducer from "./verifiedVisit/reducers/verifiedVisitReducer"
import ForgotPasswordReducer from "./forgotPassword/reducers/ForgotPasswordReducer"

const store = createStore(combineReducers({LoginReducer,ProfileReducer,MyjsReducer,PhotoReducer,AlbumReducer,verifiedVisitReducer,ForgotPasswordReducer}),{},applyMiddleware(thunk));

export default store;
