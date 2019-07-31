import '../../style/circularLoader.css';
import React from 'react';

const CircularLoader = () => {
  return (
    <div style={{
      'width': '80vw',
      'height': '100%',
      'zIndex': 10,
      'position': 'absolute',
      'backgroundColor': '#2c3037'
    }}>
      <div style={{
        position: 'absolute',
        top: '40%',
        left: '50%',
        'msTransform': 'translateX(-50%) translateY(-50%)',
        'WebkitTransform': 'translate(-50%,-50%)',
        'transform': 'translate(-50%,-50%)',
      }
      }>
        <div style={{color: "#FFF"}} className="la-ball-clip-rotate">
          <div></div>
        </div>
      </div>
    </div>


  )
}
export default CircularLoader;