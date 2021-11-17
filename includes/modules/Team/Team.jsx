// External Dependencies
import React, { Component } from 'react';

class DicmTeam extends Component {

  static slug = 'dicm_team';

  /**
   * Module render in VB
   * Basically DICM_CTA_Has_VB_Support->render() equivalent in JSX
   */
  render() {
    // console.log(this);
    return (
      <div>
        <h4 class="dicm-title">{this.props.title}</h4>
        <div
          dangerouslySetInnerHTML={{
            __html: this.props.body
          }} class="dicm-body">
        </div>
        <div class="dicm-content dicm_team_content">{this.props.content}</div>
      </div>
    );
  }
}

export default DicmTeam;
