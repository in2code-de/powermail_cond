export default class Utility {

  static hideElement(element) {
    if (element !== null) {
      element.style.display = 'none';
    }
  }

  static showElement(element) {
    if (element !== null) {
      element.style.display = 'block';
    }
  }
}
