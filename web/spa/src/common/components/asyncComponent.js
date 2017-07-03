import React from 'react';
import jsb9CommonTracking from '../../common/components/jsb9CommonTracking';

export default (loader, collection,trackRedirectionJsb9) => (
  class AsyncComponent extends React.Component {
    constructor(props) {
      let jsb9RedirectionTime = new Date().getTime();
      super(props);

// jsb9 tracking
      if(typeof trackRedirectionJsb9 != 'undefined'  && trackRedirectionJsb9)
      {
        console.log('hrer1');
  //      this.jsb9Obj = new jsb9CommonTracking();
      //  this.jsb9Obj.recordRedirection(jsb9RedirectionTime,window.location.href);

      }
// jsb9 tracking ends here
      this.Component = null;
      this.state = { Component: AsyncComponent.Component };
    }

    componentWillMount() {
      if (!this.state.Component) {
        loader().then((Component) => {
          let bundleReceivedTime = new Date().getTime();
          AsyncComponent.Component = Component;

          this.setState({ Component });
      //    this.jsb9Obj.recordBundleReceived(bundleReceivedTime);

        });
      }
      else {

        let bundleReceivedTime = new Date().getTime();
        this.jsb9Obj.recordBundleReceived(bundleReceivedTime);

      }
    }

    render() {
      if (this.state.Component) {
        return (
          <this.state.Component { ...this.props } { ...collection } />
        )
      }

      return null;
    }
  }
);
