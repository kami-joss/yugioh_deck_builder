const monsterAttributes = [
  {
    label: "Earth",
    value: "EARTH",
  },
  {
    label: "Water",
    value: "WATER",
  },
  {
    label: "Fire",
    value: "FIRE",
  },
  {
    label: "Wind",
    value: "WIND",
  },
  {
    label: "Light",
    value: "LIGHT",
  },
  {
    label: "Dark",
    value: "DARK",
  },
  {
    label: "Divine",
    value: "DIVINE",
  },
];

const monsterTypes = [
  {
    label: "Normal",
    value: "normal",
  },
  {
    label: "Effect",
    value: "effect",
  },
  {
    label: "Fusion",
    value: "fusion",
  },
  {
    label: "Ritual",
    value: "ritual",
  },
  {
    label: "Synchro",
    value: "synchro",
  },
  {
    label: "Xyz",
    value: "xyz",
  },
  {
    label: "Pendulum",
    value: "pendulum",
  },
  {
    label: "Link",
    value: "link",
  },
];

const monsterRaces = [
  {
    label: "Warrior",
    value: "warrior",
  },
  {
    label: "Spellcaster",
    value: "spellcaster",
  },
  {
    label: "Fairy",
    value: "fairy",
  },
  {
    label: "Fiend",
    value: "fiend",
  },
  {
    label: "Zombie",
    value: "zombie",
  },
  {
    label: "Machine",
    value: "machine",
  },
  {
    label: "Aqua",
    value: "aqua",
  },
  {
    label: "Pyro",
    value: "pyro",
  },
  {
    label: "Rock",
    value: "rock",
  },
  {
    label: "Winged Beast",
    value: "winged-beast",
  },
  {
    label: "Plant",
    value: "plant",
  },
  {
    label: "Insect",
    value: "insect",
  },
  {
    label: "Thunder",
    value: "thunder",
  },
  {
    label: "Dragon",
    value: "dragon",
  },
  {
    label: "Beast",
    value: "beast",
  },
  {
    label: "Beast-Warrior",
    value: "beast-warrior",
  },
  {
    label: "Dinosaur",
    value: "dinosaur",
  },
  {
    label: "Fish",
    value: "fish",
  },
  {
    label: "Sea Serpent",
    value: "sea-serpent",
  },
  {
    label: "Reptile",
    value: "reptile",
  },
  {
    label: "Psychic",
    value: "psychic",
  },
  {
    label: "Divine-Beast",
    value: "divine-beast",
  },
  {
    label: "Creator God",
    value: "creator-god",
  },
  {
    label: "Wyrm",
    value: "wyrm",
  },
  {
    label: "Cyberse",
    value: "cyberse",
  },
  {
    label: "Toon",
    value: "toon",
  },
];

const spellRaces = [
  {
    label: "Normal",
    value: "normal spell",
  },
  {
    label: "Continuous",
    value: "continuous spell",
  },
  {
    label: "Equip",
    value: "equip spell",
  },
  {
    label: "Field",
    value: "field spell",
  },
  {
    label: "Quick-Play",
    value: "quick-play spell",
  },
  {
    label: "Ritual",
    value: "ritual spell",
  },
];

const trapRaces = [
  {
    label: "Normal",
    value: "normal trap",
  },
  {
    label: "Continuous",
    value: "continuous trap",
  },
  {
    label: "Counter",
    value: "counter trap",
  },
];

const isExtraDeck = (type) => {
  const typeLower = type.toLowerCase();
  return (
    typeLower.includes("xyz") ||
    typeLower.includes("synchro") ||
    typeLower.includes("link") ||
    typeLower.includes("pendulum") ||
    typeLower.includes("fusion")
  );
};

export {
  monsterAttributes,
  monsterTypes,
  monsterRaces,
  spellRaces,
  trapRaces,
  isExtraDeck,
};
