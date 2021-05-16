import React from "react";
import Hole from "./hole";

class Board extends React.Component {
    
    constructor(props) {
        super(props);
    }

    renderHole(index, active = true) {
        let action = null;
        
        if (active) {
            action = () => this.props.onClick(index);
        }

        return (
            <Hole
                value={this.props.holes[index]}
                onClick={action}
            />
        );
    }

    render() {
        if (this.props.pointOfView == 0) {
            let canPlay = (this.props.currentPlayer == 0);

            return (
                <div className="board">
                    <div className="range">
                        {this.renderHole(11, false)}
                        {this.renderHole(10, false)}
                        {this.renderHole(9, false)}
                        {this.renderHole(8, false)}
                        {this.renderHole(7, false)}
                        {this.renderHole(6, false)}
                    </div>
                    <div className="range">
                        {this.renderHole(0, canPlay && (this.props.holes[0] > 0))}
                        {this.renderHole(1, canPlay && (this.props.holes[1] > 0))}
                        {this.renderHole(2, canPlay && (this.props.holes[2] > 0))}
                        {this.renderHole(3, canPlay && (this.props.holes[3] > 0))}
                        {this.renderHole(4, canPlay && (this.props.holes[4] > 0))}
                        {this.renderHole(5, canPlay && (this.props.holes[5] > 0))}
                    </div>
                </div>
            );
        }

        if (this.props.pointOfView == 1) {
            let canPlay = (this.props.currentPlayer == 1);

            return (
                <div className="board">
                    <div className="range">
                        {this.renderHole(5, false)}
                        {this.renderHole(4, false)}
                        {this.renderHole(3, false)}
                        {this.renderHole(2, false)}
                        {this.renderHole(1, false)}
                        {this.renderHole(0, false)}
                    </div>
                    <div className="range">
                        {this.renderHole(6, canPlay && (this.props.holes[6] > 0))}
                        {this.renderHole(7, canPlay && (this.props.holes[7] > 0))}
                        {this.renderHole(8, canPlay && (this.props.holes[8] > 0))}
                        {this.renderHole(9, canPlay && (this.props.holes[9] > 0))}
                        {this.renderHole(10, canPlay && (this.props.holes[10] > 0))}
                        {this.renderHole(11, canPlay && (this.props.holes[11] > 0))}
                    </div>
                </div>
            );
        }

        return (
            <div class='alert alert-error'>
                Point de vue non déterminé
            </div>
        );
    }
}

export default Board;