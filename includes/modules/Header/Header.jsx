// External Dependencies
import React, { Component } from 'react';


class DicmHeader extends Component {

  static slug = 'dicm_header';

  _renderImage(){
    if(this.props.image){
      return (
        <div class="image_wrapper"><img src={this.props.image} alt={this.props.title} /></div>
      );
    }
    return '';
  }

  _renderLogin(){
    if(this.props.login === 'on'){
      return (
        <a class="login_button" href="#!">Login</a>
      );
    }
    return '';
  }

  _renderSearch(){
    if(this.props.search === 'on'){
      return (
        <div class="main_search">
          <input type="text" name="s" placeholder="Search" />
        </div>
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
        <h2 className="dicm-title">{this.props.title}</h2>
        <form action="/">
          {this._renderLogin()}
          {this._renderSearch()}
        </form>
      </div>
    );
  }
}

export default DicmHeader;
