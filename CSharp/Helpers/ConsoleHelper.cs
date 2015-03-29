using System;
using System.Collections.Generic;

namespace N2f
{
	/// <summary>
	/// Class to ease interaction with CLI.
	/// </summary>
	public class ConsoleHelper
	{
		private List<ParsedArgument> _ParsedArguments;
		private string[] _Arguments;
		private bool _IsWindows;
		private bool _ForceCLI;
		private bool _IsCLI;

		/// <summary>
		/// Collection of arguments that are sorted and combined.
		/// </summary>
		public List<ParsedArgument> ParsedArguments { get { return new List<ParsedArgument>(this._ParsedArguments); } }
		/// <summary>
		/// Collection of arguments passed to the instance.
		/// </summary>
		public List<string> Arguments { get { return new List<string>(this._Arguments); } }
		/// <summary>
		/// Whether or not current execution is in a Windows based environment.
		/// </summary>
		public bool IsWindows { get { return this._IsWindows; } }
		/// <summary>
		/// Whether or not CLI emulation is setup.
		/// </summary>
		public bool ForcedCLI { get { return this._ForceCLI; } }
		/// <summary>
		/// Whether or not the execution is from CLI.
		/// </summary>
		public bool IsCLI { get { return this._ForceCLI || this._IsCLI; } }

		/// <summary>
		/// Creates a new default ConsoleHelper instance.
		/// </summary>
		public ConsoleHelper() : this(new string[0]) { }

		/// <summary>
		/// Creates a new ConsoleHelper instance.
		/// </summary>
		/// <param name="Arguments">Array of strings passed as arguments to the program.</param>
		/// <param name="ForceCLI">Whether or not to emulate CLI invocation.</param>
		public ConsoleHelper(string[] Arguments, bool ForceCLI = false)
		{
			try
			{
				Console.Title = "Testing";
				this._IsCLI = true;
			}
			catch (Exception e)
			{
				this._IsCLI = false;
			}

			switch (Environment.OSVersion.Platform)
			{
				case PlatformID.MacOSX:
				case PlatformID.Unix:
					this._IsWindows = false;

					break;
				default:
					this._IsWindows = true;

					break;
			}

			this._Arguments = (Arguments != null) ? Arguments : new string[0];
			this._ParsedArguments = this.ParseArguments(this._Arguments);
			this._ForceCLI = ForceCLI;

			return;
		}

		/// <summary>
		/// Parses a group of command line arguments into a collection of <see cref="ParsedArgument"/> values.
		/// </summary>
		/// <remarks>
		/// This method attempts to validate arguments and omits any that it deems to be invalid.  Groupings
		/// are done by looking for pairs that match any of the following patterns:
		/// 
		/// -toggle
		/// -key=value
		/// -key-value
		/// -key value
		/// 
		/// Will also match those starting with a double '-' the same way.  Any starting with an '=' or a triple
		/// '-' will be considered invalid and skipped.
		/// </remarks>
		/// <param name="Arguments">Array of arguments to parse and combine.</param>
		/// <returns>Collection of <see cref="ParsedArgument"/> values.</returns>
		public List<ParsedArgument> ParseArguments(string[] Arguments)
		{
			List<ParsedArgument> Ret = new List<ParsedArgument>();

			if (Arguments == null || Arguments.Length < 1)
			{
				return Ret;
			}

			for (int i = 0; i < Arguments.Length; ++i)
			{
				if (Arguments[i].StartsWith("-") && Arguments[i].Length > 1)
				{
					ParsedArgument Tmp = new ParsedArgument() { Key = string.Empty, Original = Arguments[i], Value = string.Empty };
					string Arg = Arguments[i].Substring((Arguments[i].StartsWith("--")) ? 2 : 1);
					int EqIndex = Arg.IndexOf('=');
					int DsIndex = Arg.IndexOf('-');

					if (EqIndex > 1 && EqIndex != Arg.Length)
					{
						Tmp.Key = Arg.Substring(0, EqIndex);
						Tmp.Value = Arg.Substring(EqIndex + 1);
					}
					else if (DsIndex > 1 && DsIndex != Arg.Length)
					{
						Tmp.Key = Arg.Substring(0, DsIndex);
						Tmp.Value = Arg.Substring(DsIndex + 1);
					}
					else if ((i + 1) < Arguments.Length && (!Arguments[i + 1].StartsWith("-") && !Arguments[i + 1].StartsWith("=")))
					{
						Tmp.Key = Arg;
						Tmp.Original += " " + Arguments[i + 1];
						Tmp.Value = Arguments[++i];
					}
					else
					{
						Tmp.Key = Arg;
						Tmp.Value = "true";
					}

					if (string.IsNullOrWhiteSpace(Tmp.Key) && string.IsNullOrWhiteSpace(Tmp.Value))
					{
						continue;
					}

					Tmp.LoweredKey = Tmp.Key.ToLower();
					Ret.Add(Tmp);
				}
			}

			return Ret;
		}
	}
}
