import React from "react";

function Hole(props) {
    return (
        <a className="hole" onClick={props.onClick}>
            <div className="stone">
                {props.value}
            </div>
        </a>
    );
}

export default Hole;