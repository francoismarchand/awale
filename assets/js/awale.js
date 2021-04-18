import React from "react";
import ReactDom from "react-dom";
import Board from "./board";

class Game extends React.Component {
    //TODO, rendre inactif les trous de l'adversaire
    constructor(props) {
        super(props);
        this.initWebsocket(this.props.url, this.props.uuid, this.props.joueur);
        this.state = {
            board: [4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4],
            scores: [0, 0],
            stepNumber: 0,
            currentPlayer: 0
        };
    }

    initWebsocket(url, uuid, joueur)
    {
        console.log(url + "/" + uuid + "/" + joueur);
        this.webSocket = new WebSocket(url + "/" + uuid + "/" + joueur);
        var that = this;

        this.webSocket.onopen = function() {
            this.onmessage = function(event) {
                try {
                    var data = JSON.parse(event.data);
                } catch (e) {
                    return;
                }

                //récupération du board
                //mette à jours le state

                console.log(data);

                that.setState({
                    board: data.board,
                    scores: data.scores
                });
                
            };
            this.onclose = function() {};
            this.onerror = function() {};
        }
    }

    onClick(holePlayed) {
        //TODO envoie de la case jouée
        console.log('Click trou ' + holePlayed);
        this.webSocket.send(JSON.stringify({
            'partie': this.props.uuid, 
            'player': 1, 
            'case': holePlayed 
        }));

        //TODO enlever tout la partie locale
        /*let holes = this.state.holes;
        let scores = this.state.scores;
        let hand = 0;
        let nbStones = holes[holePlayed];
        let nbLaps = Math.floor(nbStones / 12);
        let currentPlayer = this.state.currentPlayer;

        if ((currentPlayer == 0 && holePlayed > 5) || (currentPlayer == 1 && holePlayed < 6)) {
            return;//trous de l'adversaire
        }

        nbStones += nbLaps;//On ajoute un déplacement pour sauter la case de départ
        holes[holePlayed] = 0;

        for (let i = 1; i <= nbStones; i++) {
            hand = holePlayed + i;
           
            if (hand >= 12) {//On boucle le tour
                hand -= 12 * (nbLaps == 0 ? 1 : nbLaps);
            } 

            if (hand != holePlayed) {
                holes[hand] += 1;
            }        
        }

        //gestion des trous à manger
        while(
            (holes[hand] == 2 || holes[hand] == 3)
            &&
            (currentPlayer == 0 && hand > 5 || currentPlayer == 1 && hand < 6)//Et qu'on est du bon côté du plateau en fonction du joueur courant
         ) {
            scores[currentPlayer] += holes[hand];
            holes[hand] = 0;
            hand--;
        }

        this.state.stepNumber++;
        this.state.currentPlayer = ((this.state.stepNumber % 2) === 0 )? 0 : 1
    
        this.setState({
            holes: holes,
            scores: scores,
        });*/
    }

    calculateWinner() {
        if (this.state.scores[0] > 24) {
            return 'Joueur 1';
        }
    
        if (this.state.scores[1] > 24) {
            return 'Joueur 2';
        }
    
        return null;
    }

    render() {
        const winner = this.calculateWinner();

        let status;
        if (winner) {
            status = "Winner: " + winner;
        } else {
            status = "Next player: " + (this.state.currentPlayer + 1);
        }

        return (
            <div className="game">
                <div className="game-board">
                    <Board
                        holes={this.state.board}
                        onClick={i => this.onClick(i)}
                    />
                </div>
                <div className="game-info">
                    <div>{status}</div>
                    <div>Joueur 1 : {this.state.scores[0]}</div>
                    <div>Joueur 2 : {this.state.scores[1]}</div>
                </div>
            </div>
        );
    }
}

//TODO sortir ça dans un autre fichier
let partie = document.getElementById("js-awale");
ReactDom.render(<Game 
        uuid={partie.dataset.uuid} 
        url={partie.dataset.url} 
        joueur={partie.dataset.joueur}/>, 
    partie
);
