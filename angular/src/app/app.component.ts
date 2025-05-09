import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';

interface Book {
  id: number;
  title: string;
  author: string;
  price: number;
}

interface ApiResponse {
  success?: string;
  error?: string;
}

@Component({
  selector: 'app-root',
  template: `
    <div class="container">
      <h1>Book Management</h1>
      
      <div class="form-group">
        <h2>Add New Book</h2>
        <input [(ngModel)]="newBook.title" placeholder="Title">
        <input [(ngModel)]="newBook.author" placeholder="Author">
        <input [(ngModel)]="newBook.price" type="number" placeholder="Price">
        <button (click)="addBook()">Add Book</button>
        <p *ngIf="error" class="error">{{error}}</p>
        <p *ngIf="success" class="success">{{success}}</p>
      </div>

      <div class="books-list">
        <h2>Books</h2>
        <div *ngFor="let book of books" class="book-item">
          <div *ngIf="editingBook?.id !== book.id">
            <p>Title: {{book.title}}</p>
            <p>Author: {{book.author}}</p>
            <p>Price: {{book.price}}</p>
            <button (click)="startEdit(book)">Edit</button>
            <button (click)="deleteBook(book.id)">Delete</button>
          </div>
          <div *ngIf="editingBook?.id === book.id && editingBook">
            <input [(ngModel)]="editingBook.title" placeholder="Title">
            <input [(ngModel)]="editingBook.author" placeholder="Author">
            <input [(ngModel)]="editingBook.price" type="number" placeholder="Price">
            <button (click)="updateBook()">Save</button>
            <button (click)="cancelEdit()">Cancel</button>
          </div>
        </div>
      </div>
    </div>
  `,
  styles: [`
    .container { padding: 20px; }
    .form-group { margin-bottom: 20px; }
    input { margin: 5px; padding: 5px; }
    button { margin: 5px; padding: 5px 10px; }
    .book-item { border: 1px solid #ccc; padding: 10px; margin: 10px 0; }
    .error { color: red; }
    .success { color: green; }
  `]
})
export class AppComponent implements OnInit {
  books: Book[] = [];
  newBook: Book = { id: 0, title: '', author: '', price: 0 };
  editingBook: Book | null = null;
  error: string = '';
  success: string = '';

  constructor(private http: HttpClient) {}

  ngOnInit() {
    this.loadBooks();
  }

  loadBooks() {
    this.http.get<Book[]>('/api/getBooks.php').subscribe({
      next: (books) => {
        this.books = books;
        this.error = '';
      },
      error: (err: HttpErrorResponse) => {
        console.error('Error loading books:', err);
        this.error = 'Error loading books: ' + err.message;
      }
    });
  }

  addBook() {
    this.error = '';
    this.success = '';
    
    if (!this.newBook.title || !this.newBook.author || !this.newBook.price) {
      this.error = 'Please fill in all fields';
      return;
    }

    const formData = new FormData();
    formData.append('title', this.newBook.title);
    formData.append('author', this.newBook.author);
    formData.append('price', this.newBook.price.toString());

    this.http.post<ApiResponse>('/api/insertBook.php', formData).subscribe({
      next: (response) => {
        if (response.error) {
          this.error = response.error;
        } else {
          this.loadBooks();
          this.newBook = { id: 0, title: '', author: '', price: 0 };
          this.success = response.success || 'Book added successfully';
        }
      },
      error: (err: HttpErrorResponse) => {
        console.error('Error adding book:', err);
        this.error = 'Error adding book: ' + (err.error?.error || err.message);
      }
    });
  }

  startEdit(book: Book) {
    this.editingBook = { ...book };
    this.error = '';
    this.success = '';
  }

  cancelEdit() {
    this.editingBook = null;
    this.error = '';
    this.success = '';
  }

  updateBook() {
    if (this.editingBook) {
      this.error = '';
      this.success = '';
      
      const formData = new FormData();
      formData.append('id', this.editingBook.id.toString());
      formData.append('title', this.editingBook.title);
      formData.append('author', this.editingBook.author);
      formData.append('price', this.editingBook.price.toString());

      this.http.post<ApiResponse>('/api/updateBook.php', formData).subscribe({
        next: (response) => {
          if (response.error) {
            this.error = response.error;
          } else {
            this.loadBooks();
            this.editingBook = null;
            this.success = response.success || 'Book updated successfully';
          }
        },
        error: (err: HttpErrorResponse) => {
          console.error('Error updating book:', err);
          this.error = 'Error updating book: ' + (err.error?.error || err.message);
        }
      });
    }
  }

  deleteBook(id: number) {
    this.error = '';
    this.success = '';
    
    const formData = new FormData();
    formData.append('id', id.toString());

    this.http.post<ApiResponse>('/api/deleteBook.php', formData).subscribe({
      next: (response) => {
        if (response.error) {
          this.error = response.error;
        } else {
          this.loadBooks();
          this.success = response.success || 'Book deleted successfully';
        }
      },
      error: (err: HttpErrorResponse) => {
        console.error('Error deleting book:', err);
        this.error = 'Error deleting book: ' + (err.error?.error || err.message);
      }
    });
  }
} 