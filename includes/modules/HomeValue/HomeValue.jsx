// External Dependencies
import React, { Component } from 'react';


class DicmHomeValue extends Component {

  static slug = 'dicm_home_value';

  _renderImage(){
    if(this.props.image){
      return (
        <div class="image_wrapper"><img src={this.props.image} alt={this.props.title} /></div>
      );
    }
    return '';
  }

  /**
   * Module render in VB
   * Basically DICM_CTA_Has_VB_Support->render() equivalent in JSX
   */
  render() {
    return (
      <div>
        {this._renderImage()}
        <div>
          <h4 className="dicm-title">{this.props.title}</h4>
          <div className="dicm-content">{this.props.content()}</div>
          <form>
            <input type="text" class="input" name="address" placeholder="Home address" /> 
            <input type="text" class="input" name="zip" placeholder="Zip code" /> 
            <input type="text" class="input" name="first_name" placeholder="First name" /> 
            <input type="text" class="input" name="last_name" placeholder="Last name" /> 
            <input type="text" class="input" name="email" placeholder="Email address" /> 
            <input type="button" class="et_pb_button" value="Get Home Value" />
          </form>
        </div>
      </div>
    );
  }
}

export default DicmHomeValue;
