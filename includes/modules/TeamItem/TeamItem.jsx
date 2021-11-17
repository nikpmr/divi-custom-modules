// External Dependencies
import React, { Component } from 'react';


class DicmTeamItem extends Component {

  static slug = 'dicm_team_item';

  _renderImage(){
    if(this.props.image){
      return (
        <div className="image_wrapper" style={{backgroundImage: "url(" + this.props.image + ")"}}></div>
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
        <h4 className="dicm-title">{this.props.title}</h4>
        <div className="dicm-content">{this.props.content()}</div>
      </div>
    );
  }
}

export default DicmTeamItem;
