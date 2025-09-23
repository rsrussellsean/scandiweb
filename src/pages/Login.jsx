import { useState } from "react";
import { useNavigate } from "react-router-dom";

export default function Login({ onLogin }) {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const navigate = useNavigate();

  const handleSubmit = (e) => {
    e.preventDefault();

    // Dummy credentials
    if (username === "admin" && password === "1234") {
      setError("");
      onLogin();
      navigate("/all"); // Redirect after login
    } else {
      setError("Invalid username or password");
    }
  };

  return (
    <div>
      <div>
        {/* Green background only for this part */}
        <div className="mt-30 max-w-sm mx-auto text-center bg-green-500 text-white p-4 rounded">
          <h2>Username: admin</h2>
          <h2>Password: 1234</h2>
        </div>
      </div>

      <div className="max-w-sm mx-auto mt-10 p-4 border border-gray-300 rounded">
        <h2 className="text-2xl mb-4 text-center">Login</h2>
        <form onSubmit={handleSubmit} className="flex flex-col gap-3">
          <input
            type="text"
            placeholder="Username"
            className="border border-gray-300 p-2"
            value={username}
            onChange={(e) => setUsername(e.target.value)}
            data-testid="username-form"
          />
          <input
            type="password"
            placeholder="Password"
            className="border border-gray-300 p-2"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            data-testid="password-form"
          />
          <button
            type="submit"
            className="bg-blue-500 text-white p-2 rounded cursor-pointer"
            data-testid="login-button"
          >
            Login
          </button>
          <button
            onClick={() => {
              navigate("/signup");
            }}
            className="bg-gray-500 text-white p-2 rounded cursor-pointer"
            data-testid="signup-button"
          >
            Sign up
          </button>
          {error && <p className="text-red-500">{error}</p>}
        </form>
      </div>
    </div>
  );
}
