export default class Utility {

  static hideElement(element) {
    if (element !== null) {
      element.classList.add('powermail-cond-hidden');
      element.style.display = 'none';
    }
  }

  static showElement(element) {
    if (element !== null) {
      element.classList.remove('powermail-cond-hidden');
      element.style.display = '';
    }
  }
}
