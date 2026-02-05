export type Suit = 'hearts' | 'diamonds' | 'clubs' | 'spades';

export type GameStatus = 'playing' | 'won' | 'abandoned';

export type LocationType = 'stock' | 'waste' | 'foundation' | 'tableau';

export interface Card {
    suit: Suit;
    rank: number;
    faceUp: boolean;
}

export interface MoveLocation {
    type: LocationType;
    index?: number | string | null;
}

export interface GameState {
    stock: Card[];
    waste: Card[];
    foundations: {
        hearts: Card[];
        diamonds: Card[];
        clubs: Card[];
        spades: Card[];
    };
    tableaus: Card[][];
}

export interface Game {
    id: string;
    status: GameStatus;
    moveCount: number;
    score: number;
    state: GameState;
    canUndo?: boolean;
}

export interface MovePayload {
    from: MoveLocation;
    to: MoveLocation;
    cards: Card[];
}

export interface GameResponse {
    success: boolean;
    game?: Game;
    error?: string;
}

export const RED_SUITS: Suit[] = ['hearts', 'diamonds'];
export const BLACK_SUITS: Suit[] = ['clubs', 'spades'];

export function isRedSuit(suit: Suit): boolean {
    return RED_SUITS.includes(suit);
}

export function isBlackSuit(suit: Suit): boolean {
    return BLACK_SUITS.includes(suit);
}

export function getRankDisplay(rank: number): string {
    switch (rank) {
        case 1:
            return 'A';
        case 11:
            return 'J';
        case 12:
            return 'Q';
        case 13:
            return 'K';
        default:
            return rank.toString();
    }
}

export function getSuitSymbol(suit: Suit): string {
    switch (suit) {
        case 'hearts':
            return '\u2665';
        case 'diamonds':
            return '\u2666';
        case 'clubs':
            return '\u2663';
        case 'spades':
            return '\u2660';
    }
}
